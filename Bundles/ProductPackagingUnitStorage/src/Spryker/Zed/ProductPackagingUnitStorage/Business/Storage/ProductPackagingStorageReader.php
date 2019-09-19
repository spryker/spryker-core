<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface;
use Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface;

class ProductPackagingStorageReader implements ProductPackagingStorageReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface
     */
    protected $productPackagingUnitStorageRepository;

    /**
     * @var \Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface
     */
    protected $productPackagingUnitFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface $productPackagingUnitStorageRepository
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface $productPackagingUnitFacade
     */
    public function __construct(
        ProductPackagingUnitStorageRepositoryInterface $productPackagingUnitStorageRepository,
        ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface $productPackagingUnitFacade
    ) {
        $this->productPackagingUnitStorageRepository = $productPackagingUnitStorageRepository;
        $this->productPackagingUnitFacade = $productPackagingUnitFacade;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer[]
     */
    public function getProductConcretePackagingStorageTransfer(array $productConcreteIds): array
    {
        $productConcretePackagingStorageTransfers = [];
        $productPackagingUnitEntityTransfers = $this->getPackagingProductsByConcreteIds($productConcreteIds);
        foreach ($productPackagingUnitEntityTransfers as $productPackagingUnitEntityTransfer) {
            $productConcretePackagingStorageTransfers[] = $this->createProductConcretePackagingStorageTransfer(
                $productPackagingUnitEntityTransfer
            );
        }

        return $productConcretePackagingStorageTransfers;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcretePackagingStorageEntityTransfer[]
     */
    public function getProductConcretePackagingStorageEntities(array $productConcreteIds): array
    {
        return $this->productPackagingUnitStorageRepository
            ->findProductConcretePackagingStorageEntitiesByProductConcreteIds($productConcreteIds);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer[]
     */
    protected function getPackagingProductsByConcreteIds(array $productConcreteIds): array
    {
        return $this->productPackagingUnitStorageRepository->findPackagingProductsByProductConcreteIds($productConcreteIds);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer
     */
    protected function createProductConcretePackagingStorageTransfer(
        SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntityTransfer
    ): ProductConcretePackagingStorageTransfer {
        return (new ProductConcretePackagingStorageTransfer())
            ->fromArray($productPackagingUnitEntityTransfer->toArray(), true)
            ->setIdLeadProduct($productPackagingUnitEntityTransfer->getLeadProduct()->getIdProduct())
            ->setIdProduct($productPackagingUnitEntityTransfer->getFkProduct())
            ->setTypeName($productPackagingUnitEntityTransfer->getProductPackagingUnitType()->getName());
    }
}
