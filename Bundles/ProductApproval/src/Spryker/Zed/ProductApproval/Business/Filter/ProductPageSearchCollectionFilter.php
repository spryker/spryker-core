<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Filter;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Zed\ProductApproval\Business\Reader\ProductReaderInterface;

class ProductPageSearchCollectionFilter implements ProductPageSearchCollectionFilterInterface
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
     * @param array<\Generated\Shared\Transfer\ProductPageSearchTransfer> $productPageSearchTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductPageSearchTransfer>
     */
    public function filterProductPageSearchCollection(array $productPageSearchTransfers): array
    {
        $productAbstractSkus = array_map(function (ProductPageSearchTransfer $productPageSearchTransfers) {
            return $productPageSearchTransfers->getSkuOrFail();
        }, $productPageSearchTransfers);

        $productAbstractTransfersIndexedByIdProductAbstract = $this->productReader
            ->getProductAbstractTransfersIndexedByIdProductAbstract($productAbstractSkus);

        $filteredProductPageSearchTransfers = [];
        foreach ($productPageSearchTransfers as $productPageSearchTransfer) {
            $idProductAbstract = $productPageSearchTransfer->getIdProductAbstractOrFail();
            $productAbstractTransfer = $productAbstractTransfersIndexedByIdProductAbstract[$idProductAbstract];

            if ($productAbstractTransfer->getApprovalStatus() === ProductApprovalConfig::STATUS_APPROVED) {
                $filteredProductPageSearchTransfers[] = $productPageSearchTransfer;
            }
        }

        return $filteredProductPageSearchTransfers;
    }
}
