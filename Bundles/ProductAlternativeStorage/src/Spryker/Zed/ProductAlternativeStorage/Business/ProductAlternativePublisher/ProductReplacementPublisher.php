<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher;


use Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer;
use Spryker\Zed\ProductAlternativeStorage\Business\Model\ProductAlternativeStorageReaderInterface;
use Spryker\Zed\ProductAlternativeStorage\Dependency\Facade\ProductAlternativeStorageToProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface;

class ProductReplacementPublisher implements ProductAlternativePublisherInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Dependency\Facade\ProductAlternativeStorageToProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface
     */
    protected $productAlternativeStorageRepository;

    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface
     */
    protected $productAlternativeStorageEntityManager;

    /**
     * @param \Spryker\Zed\ProductAlternativeStorage\Dependency\Facade\ProductAlternativeStorageToProductAlternativeFacadeInterface $productAlternativeFacade
     * @param \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface $productAlternativeStorageRepository
     * @param \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface $productAlternativeStorageEntityManager
     */
    public function __construct(
        ProductAlternativeStorageToProductAlternativeFacadeInterface $productAlternativeFacade,
        ProductAlternativeStorageRepositoryInterface $productAlternativeStorageRepository,
        ProductAlternativeStorageEntityManagerInterface $productAlternativeStorageEntityManager
    ) {
        $this->productAlternativeFacade = $productAlternativeFacade;
        $this->productAlternativeStorageRepository = $productAlternativeStorageRepository;
        $this->productAlternativeStorageEntityManager = $productAlternativeStorageEntityManager;
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publish(array $productIds): void
    {
        $mappedProductConcreteAlternativeStorageEntityTransfer =
            $this->findMappedProductConcreteAlternativeStorageEntityTransfers($productIds);

        foreach ($productIds as $idProduct) {
            $storageEntitiesWithStore = [];

            if (isset($mappedProductConcreteAlternativeStorageEntityTransfer[$idProduct])) {
                $storageEntitiesWithStore = $mappedProductConcreteAlternativeStorageEntityTransfer[$idProduct];
            }

            unset($mappedProductConcreteAlternativeStorageEntityTransfer[$idProduct]);

            $this->saveStorageEntityTransfer($idProduct, $storageEntitiesWithStore);
        }
    }

    /**
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer[]
     *
     * @return void
     */
    protected function saveStorageEntityTransfer(int $idProduct, array $storageEntityTransfers): void
    {
        $productConcreteAlternativeStorageData = $this->generateProductAlternativeStorageTransfersByIdProduct($idProduct);

        foreach ($productConcreteAlternativeStorageData as $storageData) {

        }

    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeStorageTransfer[]
     */
    protected function generateProductAlternativeStorageTransfersByIdProduct(int $idProduct): array
    {
    }

    /**
     * @param int[] $productIds
     *
     * @return array
     */
    protected function findMappedProductConcreteAlternativeStorageEntityTransfers(array $productIds): array
    {
        return;
    }
}
