<?php

namespace App\Client;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface CurrencyClientInterface
{
    public function __construct(HttpClientInterface $client, CacheInterface $cache);
    public function fetchPair($pair = null): ?array;    
}
