<?php

namespace App\Client;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyClient implements CurrencyClientInterface
{
    private $accessKey = 'H3rgmhhc2ATic0BLH8fm9a';
    private $client;
    private $cache;
    private $memoizeId;

    public function __construct(HttpClientInterface $client, CacheInterface $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    public function fetchPair($pair = null): ?array
    {
        if(!isset($pair)) {
            return null;
        }

        $id = $this->findPairId($pair);

        $response = $this->cache->get("app.pair_".$id, function() use ($id) {
            return $this->client->request(
                'GET',
                "https://fcsapi.com/api-v3/forex/latest?id={$id}&access_key=" .$this->accessKey
            );
        });

        $statusCode = $response->getStatusCode();

        if($statusCode !== 200) {
            throw new \Exception('Could not fetch currency data');
        }        

        $content = $response->toArray();
        if(isset($content['status']) && $content['status'] === false && isset($content['msg'])) {
            throw new \Exception($content['msg']);
        }      

        return $content['response'][0];
    }

    private function fetchAll()
    {        
        $response = $this->cache->get('app.all', function() {
            return $this->client->request(
                'GET', 
                "https://fcsapi.com/api-v3/forex/list?type=forex&access_key=" . $this->accessKey
            );
        });

        $statusCode = $response->getStatusCode();

        if($statusCode !== 200) {
            throw new \Exception('Could not fetch currency data');
        }

        $content = $response->toArray();
        if(isset($content['status']) && $content['status'] === false && isset($content['msg'])) {
            throw new \Exception($content['msg']);
        }
        
        $currencies = array_map(function($item) {
            $item['symbol'] = str_replace("/", " ", $item['symbol']);
            return $item;
        }, $content['response']);

        return $currencies;
    }

    private function fetchLast10($pair)
    {
        $id = $this->findPairId($pair);
        $response = $this->cache->get('app.last_' . $id, function() use($id) {
            return $this->client->request(
                'GET', 
                "https://fcsapi.com/api-v3/forex/history?id={$id}&period=1h&access_key=" . $this->accessKey
            );
        });

        $statusCode = $response->getStatusCode();

        if($statusCode !== 200) {
            throw new \Exception('Could not fetch currency data');
        }

        $content = $response->toArray();
        $last10Candles = array_slice($content['response'], 0, 10);

        return $last10Candles;
    }
    
    private function findPairId($pair)
    {
        if(isset($this->memoizeId))
        {
            return $this->memoizeId;
        }
        
        $currencies = $this->fetchAll();

        foreach($currencies as $currency) {
            
            if($currency['symbol'] == $pair) {
                $this->memoizeId = $currency['id'];
                return $currency['id'];
            }
        }

        return null;
    }

    private function calculateAvgDeviation($pair)
    {
        #0,73 ↑ or 0,73 ↓ or 0,73 -
        $last10Candles = $this->fetchLast10($pair);
        $price = $this->fetchPair($pair);
        $sum = 0;
        foreach($last10Candles as $candle) {
            $sum += floatval($candle['c']);
        }

        $mean = $sum / sizeof($last10Candles);
        $deviation = [];
        foreach($last10Candles as $candle) {
           $priceClose = floatval($candle['c']);
           $deviation[] = abs($priceClose - $mean);
        }

        $averageDeviation = array_sum($deviation) / sizeof($last10Candles);

        return $averageDeviation;
    }
}
