<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use ArrayObject;
use Generated\Shared\Transfer\ProductListResponseTransfer;

class WhitelistProductListTypeExpander extends AbstractProductListTypeExpander
{
    protected const MESSAGE_VALUE = 'product_bundle_sku was added to the whitelist with follow products product_for_bundle_skus.';

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandProductListByIdProductConcrete(int $idProductConcrete, ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        $productForBundleTransfers = $this->productBundleFacade->findBundledProductsByIdProductConcrete($idProductConcrete);

        if (!$productForBundleTransfers->count()) {
            return $productListResponseTransfer;
        }

        return $this->expandProductListWithProductForBundle(
            $idProductConcrete,
            $productForBundleTransfers,
            $productListResponseTransfer
        );
    }

    /**
     * @param int $idProductConcreteBundle
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandProductListWithProductForBundle(
        int $idProductConcreteBundle,
        ArrayObject $productForBundleTransfers,
        ProductListResponseTransfer $productListResponseTransfer
    ): ProductListResponseTransfer {
        $productIdsToAssign = $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->getProductIds();

        $productForBundleIdsToAssign = [];

        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            if (in_array($productForBundleTransfer->getIdProductConcrete(), $productIdsToAssign)) {
                continue;
            }

            $productForBundleIdsToAssign[] = $productForBundleTransfer->getIdProductConcrete();
        }

        if (!$productForBundleIdsToAssign) {
            return $productListResponseTransfer;
        }

        $messageTransfer = $this->generateMessageTransfer(static::MESSAGE_VALUE, $idProductConcreteBundle, $productForBundleIdsToAssign);
        $productListResponseTransfer->addMessage($messageTransfer);
        $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->setProductIds(array_merge($productIdsToAssign, $productForBundleIdsToAssign));

        return $productListResponseTransfer;
    }
}
