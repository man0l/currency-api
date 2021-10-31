<?php

namespace App\Repository;

use App\Entity\Currency;

interface CurrencyRepositoryInterface
{
    public function findByPairName($pairName);
}