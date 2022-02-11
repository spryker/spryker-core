<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Filter;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface;

class ProductConcreteCollectionFilter implements ProductConcreteCollectionFilterInterface
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
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function filterProductConcreteCollection(array $productConcreteTransfers): array
    {
        $productAbstractSkus = array_map(function (ProductConcreteTransfer $productConcreteTransfer) {
            return $productConcreteTransfer->getAbstractSkuOrFail();
        }, $productConcreteTransfers);

        $productAbstractTransfers = $this->productFacade->getRawProductAbstractTransfersByAbstractSkus($productAbstractSkus);

        $approvalStatusesIndexedByIdProduct = $this->getApprovalStatusesIndexedByIdProduct(
            $productConcreteTransfers,
            $productAbstractTransfers,
        );

        $filteredProductConcreteTransfers = [];
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $idProduct = $productConcreteTransfer->getIdProductConcreteOrFail();
            if ($approvalStatusesIndexedByIdProduct[$idProduct] === ProductApprovalConfig::STATUS_APPROVED) {
                $filteredProductConcreteTransfers[] = $productConcreteTransfer;
            }
        }

        return $filteredProductConcreteTransfers;
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
