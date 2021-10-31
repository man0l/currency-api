<?php

namespace App\Repository;

use App\Entity\Currency;
use App\Repository\CurrencyRepositoryInterface;
use App\Client\CurrencyClientInterface;

class CurrencyRepository implements CurrencyRepositoryInterface
{

    private $client;
    public function __construct(CurrencyClientInterface $client)
    {
        $this->client = $client;
    }

    public function findByPairName($pairName)
    {
        $pair = $this->client->fetchPair($pairName);        
        $currency = new Currency($pairName, $pairName, "", floatval($pair['c']), new \DateTime($pair['tm']));
        return $currency;
    }
}