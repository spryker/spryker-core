<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Storage\Expander;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class ProductTypeProductConcreteStorageExpander implements ProductTypeProductConcreteStorageExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     */
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository
    ) {
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    public function expandProductConcreteStorageTransfersWithProductTypes(array $productConcreteStorageTransfers): array
    {
        if (!$productConcreteStorageTransfers) {
            return $productConcreteStorageTransfers;
        }

        $productAbstractIds = $this->extractProductAbstractIds($productConcreteStorageTransfers);

        $productAbstractTypeTransfers = $this->selfServicePortalRepository->getProductAbstractTypesByProductAbstractIds($productAbstractIds);

        return $this->expandProductsWithProductTypes($productConcreteStorageTransfers, $productAbstractTypeTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     * @param array<\Generated\Shared\Transfer\ProductAbstractTypeTransfer> $productAbstractTypeTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    protected function expandProductsWithProductTypes(array $productConcreteStorageTransfers, array $productAbstractTypeTransfers): array
    {
        $productAbstractTypeNamesIndexedByIdProductAbstract = [];

        foreach ($productAbstractTypeTransfers as $productAbstractTypeTransfer) {
            foreach ($productAbstractTypeTransfer->getFkProductAbstracts() as $fkProductAbstract) {
                $productAbstractTypeNamesIndexedByIdProductAbstract[$fkProductAbstract][] = $productAbstractTypeTransfer->getName();
            }
        }

        foreach ($productConcreteStorageTransfers as $productConcreteStorageTransfer) {
            /**
             * @var array<string>|null $productTypes
             */
            $productTypes = $productAbstractTypeNamesIndexedByIdProductAbstract[$productConcreteStorageTransfer->getIdProductAbstractOrFail()] ?? [];
            $productConcreteStorageTransfer->setProductTypes($productTypes);
        }

        return $productConcreteStorageTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return list<int>
     */
    protected function extractProductAbstractIds(array $productConcreteStorageTransfers): array
    {
        return array_map(
            static fn (ProductConcreteStorageTransfer $productConcreteStorageTransfer): int => $productConcreteStorageTransfer->getIdProductAbstractOrFail(),
            $productConcreteStorageTransfers,
        );
    }
}
