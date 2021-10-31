<?php
namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Currency;
use App\Repository\CurrencyRepositoryInterface;

final class CurrencyItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $repository;

    public function __construct(CurrencyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Currency::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Currency
    {        
        return $this->repository->findByPairName($id);
    }
}