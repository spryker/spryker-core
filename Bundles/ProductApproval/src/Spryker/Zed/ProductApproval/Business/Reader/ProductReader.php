<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Reader;

use Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface;

class ProductReader implements ProductReaderInterface
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
     * @param array<string> $productAbstractSkus
     *
     * @return array<int, \Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getProductAbstractTransfersIndexedByIdProductAbstract(array $productAbstractSkus): array
    {
        $productAbstractTransfers = $this->productFacade->getRawProductAbstractTransfersByAbstractSkus($productAbstractSkus);

        if (!count($productAbstractTransfers)) {
            return [];
        }

        $productAbstractTransfersIndexedByIdProductAbstract = [];
        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $productAbstractTransfersIndexedByIdProductAbstract[$productAbstractTransfer->getIdProductAbstractOrFail()] = $productAbstractTransfer;
        }

        return $productAbstractTransfersIndexedByIdProductAbstract;
    }
}
