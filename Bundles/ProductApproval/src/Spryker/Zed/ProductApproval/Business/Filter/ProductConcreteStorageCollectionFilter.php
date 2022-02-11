<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Filter;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface;

class ProductConcreteStorageCollectionFilter implements ProductConcreteStorageCollectionFilterInterface
{
    /**
     * @var \Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface $productFacade
     */
    public function __construct(ProductApprovalToProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    public function filterProductConcreteStorageCollection(array $productConcreteStorageTransfers): array
    {
        $productIds = array_map(function (ProductConcreteStorageTransfer $productConcreteStorageTransfer): int {
            return $productConcreteStorageTransfer->getIdProductConcreteOrFail();
        }, $productConcreteStorageTransfers);

        $productConcreteTransfers = $this->productFacade->getProductConcreteTransfersByProductIds($productIds);

        $productAbstractSkus = array_map(function (ProductConcreteTransfer $productConcreteTransfer): string {
            return $productConcreteTransfer->getAbstractSkuOrFail();
        }, $productConcreteTransfers);

        $productAbstractTransfers = $this->productFacade->getRawProductAbstractTransfersByAbstractSkus($productAbstractSkus);

        $approvalStatusesIndexedByIdProduct = $this->getApprovalStatusesIndexedByIdProduct(
            $productConcreteTransfers,
            $productAbstractTransfers,
        );

        $filteredProductConcreteStorageTransfers = [];
        foreach ($productConcreteStorageTransfers as $productConcreteStorageTransfer) {
            $idProduct = $productConcreteStorageTransfer->getIdProductConcreteOrFail();
            if ($approvalStatusesIndexedByIdProduct[$idProduct] === ProductApprovalConfig::STATUS_APPROVED) {
                $filteredProductConcreteStorageTransfers[] = $productConcreteStorageTransfer;
            }
        }

        return $filteredProductConcreteStorageTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     * @param array<\Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfers
     *
     * @return array<int, string|null>
     */
    protected function getApprovalStatusesIndexedByIdProduct(
        array $productConcreteTransfers,
        array $productAbstractTransfers
    ): array {
        $approvalStatusesIndexedByIdProductAbstract = [];
        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $idProductAbstract = $productAbstractTransfer->getIdProductAbstractOrFail();

            $approvalStatusesIndexedByIdProductAbstract[$idProductAbstract] = $productAbstractTransfer->getApprovalStatus();
        }

        $approvalStatusesIndexedByIdProduct = [];
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $idProductConcrete = $productConcreteTransfer->getIdProductConcreteOrFail();
            $idProductAbstract = $productConcreteTransfer->getFkProductAbstractOrFail();

            $approvalStatusesIndexedByIdProduct[$idProductConcrete] = $approvalStatusesIndexedByIdProductAbstract[$idProductAbstract];
        }

        return $approvalStatusesIndexedByIdProduct;
    }
}
