<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Business\ProductPage;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductListFacadeInterface;

class ProductPageDataExpander implements ProductPageDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductListFacadeInterface
     */
    private $productListFacade;

    /**
     * @param \Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductListFacadeInterface $productListFacade
     */
    public function __construct(ProductListSearchToProductListFacadeInterface $productListFacade)
    {
        $this->productListFacade = $productListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $loadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageData(ProductPageLoadTransfer $loadTransfer): ProductPageLoadTransfer
    {
        $productList = $this->productListFacade->getProductAbstractListIdsByProductAbstractIds($loadTransfer->getProductAbstractIds());

        $updatedPayloadTransfers = $this->updatePayloadTransfers(
            $loadTransfer->getPayloadTransfers(),
            $productList
        );

        $loadTransfer->setPayloadTransfers($updatedPayloadTransfers);

        return $loadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     * @param array $mappedProductListIds
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer[]
     */
    protected function updatePayloadTransfers(array $payloadTransfers, array $mappedProductListIds): array
    {
        foreach ($payloadTransfers as $payloadTransfer) {
            $lists = $mappedProductListIds[$payloadTransfer->getIdProductAbstract()] ?? null;

            $payloadTransfer->setProductLists($lists);
        }

        return $payloadTransfers;
    }
}
