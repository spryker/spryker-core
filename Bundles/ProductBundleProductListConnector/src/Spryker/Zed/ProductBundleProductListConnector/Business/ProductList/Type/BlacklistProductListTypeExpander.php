<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;

class BlacklistProductListTypeExpander extends AbstractProductListTypeExpander
{
    protected const MESSAGE_VALUE = 'product_bundle_sku was blacklisted due to blacklisting product_bundled_skus.';
    protected const PRODUCT_BUNDLE_SKU_PARAMETER = 'product_bundle_sku';
    protected const PRODUCT_BUNDLED_SKUS_PARAMETER = 'product_bundled_skus';

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function expandProductListWithProductBundle(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        foreach ($productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds() as $idProductConcreteBundled) {
            $productListResponseTransfer = $this->expandByIdProductConcrete($idProductConcreteBundled, $productListResponseTransfer);
        }

        return $productListResponseTransfer;
    }

    /**
     * @param int $idProductConcreteBundled
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandByIdProductConcrete(int $idProductConcreteBundled, ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->setIdBundledProduct($idProductConcreteBundled);
        $productBundleCollectionTransfer = $this->productBundleFacade->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer);

        if (empty($productBundleCollectionTransfer->getProductBundles())) {
            return $productListResponseTransfer;
        }

        return $this->expandByIdProductConcreteAndCollection(
            $idProductConcreteBundled,
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
    protected function expandByIdProductConcreteAndCollection(
        int $idProductConcreteBundled,
        ProductBundleCollectionTransfer $productBundleCollectionTransfer,
        ProductListResponseTransfer $productListResponseTransfer
    ): ProductListResponseTransfer {
        $productIdsToAssign = $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->getProductIds();

        foreach ($productBundleCollectionTransfer->getProductBundles() as $productBundleTransfer) {
            if (in_array($productBundleTransfer->getIdProductConcrete(), $productIdsToAssign)) {
                continue;
            }

            $productIdsToAssign[] = $productBundleTransfer->getIdProductConcrete();
            $messageTransfer = $this->generateMessageTransfer(static::MESSAGE_VALUE, $productBundleTransfer->getIdProductConcrete(), $idProductConcreteBundled);
            $productListResponseTransfer->addMessage($messageTransfer);
        }

        $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->setProductIds($productIdsToAssign);

        return $productListResponseTransfer;
    }

    /**
     * @param string $value
     * @param int $idProductConcreteBundle
     * @param int $idProductConcreteBundled
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function generateMessageTransfer(string $value, int $idProductConcreteBundle, int $idProductConcreteBundled): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($value)
            ->setParameters([
                static::PRODUCT_BUNDLE_SKU_PARAMETER => $this->getMessageTransferParameter($this->productFacade->getProductConcreteSkusByConcreteIds([$idProductConcreteBundle])),
                static::PRODUCT_BUNDLED_SKUS_PARAMETER => $this->getMessageTransferParameter($this->productFacade->getProductConcreteSkusByConcreteIds([$idProductConcreteBundled])),
            ]);
    }
}
