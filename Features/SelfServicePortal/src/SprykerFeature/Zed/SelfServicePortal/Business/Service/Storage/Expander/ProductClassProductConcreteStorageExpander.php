<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Storage\Expander;

use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class ProductClassProductConcreteStorageExpander implements ProductClassProductConcreteStorageExpanderInterface
{
    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface
     */
    protected $selfServicePortalRepository;

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     */
    public function __construct(SelfServicePortalRepositoryInterface $selfServicePortalRepository)
    {
        $this->selfServicePortalRepository = $selfServicePortalRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    public function expandProductConcreteStorageTransfersWithProductClasses(array $productConcreteStorageTransfers): array
    {
        $productConcreteIds = $this->getProductConcreteIds($productConcreteStorageTransfers);

        if (!$productConcreteIds) {
            return $productConcreteStorageTransfers;
        }

        $productClassesIndexedByIdProductConcrete = $this->selfServicePortalRepository->getProductClassesByProductConcreteIds($productConcreteIds);

        return $this->expandProductConcreteStorageTransfersWithProductClassNames(
            $productConcreteStorageTransfers,
            $productClassesIndexedByIdProductConcrete,
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return array<int>
     */
    protected function getProductConcreteIds(array $productConcreteStorageTransfers): array
    {
        $productConcreteIds = [];
        foreach ($productConcreteStorageTransfers as $productConcreteStorageTransfer) {
            $productConcreteIds[] = $productConcreteStorageTransfer->getIdProductConcreteOrFail();
        }

        return $productConcreteIds;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     * @param array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>> $productClassesIndexedByIdProductConcrete
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    protected function expandProductConcreteStorageTransfersWithProductClassNames(
        array $productConcreteStorageTransfers,
        array $productClassesIndexedByIdProductConcrete
    ): array {
        foreach ($productConcreteStorageTransfers as $productConcreteStorageTransfer) {
            $idProductConcrete = $productConcreteStorageTransfer->getIdProductConcrete();
            if (isset($productClassesIndexedByIdProductConcrete[$idProductConcrete])) {
                $productConcreteStorageTransfer->setProductClassNames($this->getProductClassNames($productClassesIndexedByIdProductConcrete[$idProductConcrete]));
            }
        }

        return $productConcreteStorageTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductClassTransfer> $productClassTransfers
     *
     * @return array<string>
     */
    protected function getProductClassNames(array $productClassTransfers): array
    {
        $productClassNames = [];

        foreach ($productClassTransfers as $productClassTransfer) {
            $productClassNames[] = $productClassTransfer->getNameOrFail();
        }

        return $productClassNames;
    }
}
