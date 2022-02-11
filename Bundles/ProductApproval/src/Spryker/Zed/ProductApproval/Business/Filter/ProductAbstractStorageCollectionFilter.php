<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Filter;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Zed\ProductApproval\Business\Reader\ProductReaderInterface;

class ProductAbstractStorageCollectionFilter implements ProductAbstractStorageCollectionFilterInterface
{
    /**
     * @var \Spryker\Zed\ProductApproval\Business\Reader\ProductReaderInterface
     */
    protected $productReader;

    /**
     * @param \Spryker\Zed\ProductApproval\Business\Reader\ProductReaderInterface $productReader
     */
    public function __construct(ProductReaderInterface $productReader)
    {
        $this->productReader = $productReader;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer> $productAbstractStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer>
     */
    public function filterProductAbstractStorageCollection(array $productAbstractStorageTransfers): array
    {
        $productAbstractSkus = array_map(function (ProductAbstractStorageTransfer $productAbstractStorageTransfer) {
            return $productAbstractStorageTransfer->getSkuOrFail();
        }, $productAbstractStorageTransfers);

        $productAbstractTransfersIndexedByIdProductAbstract = $this->productReader
            ->getProductAbstractTransfersIndexedByIdProductAbstract($productAbstractSkus);

        $filteredProductAbstractStorageTransfers = [];
        foreach ($productAbstractStorageTransfers as $productAbstractStorageTransfer) {
            $idProductAbstract = $productAbstractStorageTransfer->getIdProductAbstractOrFail();
            $productAbstractTransfer = $productAbstractTransfersIndexedByIdProductAbstract[$idProductAbstract];

            if ($productAbstractTransfer->getApprovalStatus() === ProductApprovalConfig::STATUS_APPROVED) {
                $filteredProductAbstractStorageTransfers[] = $productAbstractStorageTransfer;
            }
        }

        return $filteredProductAbstractStorageTransfers;
    }
}
