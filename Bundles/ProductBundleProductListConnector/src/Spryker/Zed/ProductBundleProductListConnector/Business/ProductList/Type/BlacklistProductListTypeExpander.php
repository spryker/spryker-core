<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;

class BlacklistProductListTypeExpander extends AbstractProductListTypeExpander
{
    protected const MESSAGE_VALUE = 'product_bundle_sku was blacklisted due to blacklisting product_for_bundle_skus.';

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandProductListByIdProductConcrete(int $idProductConcrete, ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->setIdBundledProduct($idProductConcrete);

        $productBundleCollectionTransfer = $this->productBundleFacade
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer);

        if (!$productBundleCollectionTransfer->getProductBundles()->count()) {
            return $productListResponseTransfer;
        }

        return $this->expandProductListWithBundleProduct(
            $idProductConcrete,
            $productBundleCollectionTransfer,
            $productListResponseTransfer
        );
    }

    /**
     * @param int $idProductConcreteBundled
     * @param \Generated\Shared\Transfer\ProductBundleCollectionTransfer $productBundleCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandProductListWithBundleProduct(
        int $idProductConcreteBundled,
        ProductBundleCollectionTransfer $productBundleCollectionTransfer,
        ProductListResponseTransfer $productListResponseTransfer
    ): ProductListResponseTransfer {
        $productIdsToAssign = $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->getProductIds();

        foreach ($productBundleCollectionTransfer->getProductBundles() as $productBundleTransfer) {
            if (in_array($productBundleTransfer->getIdProductConcreteBundle(), $productIdsToAssign)) {
                continue;
            }

            $productIdsToAssign[] = $productBundleTransfer->getIdProductConcreteBundle();
            $messageTransfer = $this->generateMessageTransfer(static::MESSAGE_VALUE, $productBundleTransfer->getIdProductConcreteBundle(), [$idProductConcreteBundled]);
            $productListResponseTransfer->addMessage($messageTransfer);
        }

        $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->setProductIds($productIdsToAssign);

        return $productListResponseTransfer;
    }
}
