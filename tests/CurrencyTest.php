<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class CurrencyTest extends ApiTestCase
{
    public function testFindByPair()
    {
        $pair = 'CAD CHF';
        $response = static::createClient()->request('GET', '/api/currencies/' . $pair);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Currency',
            '@id' => '/api/currencies/' . rawurlencode($pair),
            '@type' => 'Currency',
            'id' => $pair,
            'pair' => $pair
        ]);

    }    
}
