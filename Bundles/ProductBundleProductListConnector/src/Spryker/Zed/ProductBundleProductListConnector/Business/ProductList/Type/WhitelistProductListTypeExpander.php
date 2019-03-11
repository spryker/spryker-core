<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;

class WhitelistProductListTypeExpander extends AbstractProductListTypeExpander
{
    protected const MESSAGE_VALUE = 'product_bundle_sku was added to the whitelist with follow products product_bundled_skus.';
    protected const PRODUCT_BUNDLE_SKU_PARAMETER = 'product_bundle_sku';
    protected const PRODUCT_BUNDLED_SKUS_PARAMETER = 'product_bundled_skus';

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function expandProductListWithProductBundle(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        foreach ($productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds() as $idProductConcreteBundle) {
            $productListResponseTransfer = $this->expandByIdProductConcrete($idProductConcreteBundle, $productListResponseTransfer);
        }

        return $productListResponseTransfer;
    }

    /**
     * @param int $idProductConcreteBundle
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandByIdProductConcrete(int $idProductConcreteBundle, ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        $productForBundleTransfers = $this->productBundleFacade->findBundledProductsByIdProductConcrete($idProductConcreteBundle);

        if (!$productForBundleTransfers->count()) {
            return $productListResponseTransfer;
        }

        return $this->expandByIdProductConcreteAndProductForBundleTransfers(
            $idProductConcreteBundle,
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
    protected function expandByIdProductConcreteAndProductForBundleTransfers(
        int $idProductConcreteBundle,
        ArrayObject $productForBundleTransfers,
        ProductListResponseTransfer $productListResponseTransfer
    ): ProductListResponseTransfer {
        $productIdsToAssign = $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->getProductIds();
        $productForBundleIdsToAssign = $this->getProductForBundleIdsToAssign($productForBundleTransfers, $productIdsToAssign);

        if (!$productForBundleIdsToAssign) {
            return $productListResponseTransfer;
        }

        return $this->expandProductListResponseTransfer($productListResponseTransfer, $idProductConcreteBundle, $productForBundleIdsToAssign);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     * @param int[] $productIdsToAssign
     *
     * @return int[]
     */
    protected function getProductForBundleIdsToAssign(ArrayObject $productForBundleTransfers, array $productIdsToAssign): array
    {
        $productForBundleIdsToAssign = [];

        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            if (in_array($productForBundleTransfer->getIdProductConcrete(), $productIdsToAssign)) {
                continue;
            }

            $productForBundleIdsToAssign[] = $productForBundleTransfer->getIdProductConcrete();
        }

        return $productForBundleIdsToAssign;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     * @param int $idProductConcreteBundle
     * @param int[] $productForBundleIdsToAssign
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandProductListResponseTransfer(
        ProductListResponseTransfer $productListResponseTransfer,
        int $idProductConcreteBundle,
        array $productForBundleIdsToAssign
    ): ProductListResponseTransfer {
        $messageTransfer = $this->generateMessageTransfer(static::MESSAGE_VALUE, $idProductConcreteBundle, $productForBundleIdsToAssign);
        $productIdsToAssign = $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->getProductIds();

        $productListResponseTransfer->addMessage($messageTransfer);
        $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->setProductIds(array_merge($productIdsToAssign, $productForBundleIdsToAssign));

        return $productListResponseTransfer;
    }

    /**
     * @param string $value
     * @param int $idProductConcreteBundle
     * @param int[] $productForBundleIdsToAssign
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function generateMessageTransfer(string $value, int $idProductConcreteBundle, array $productForBundleIdsToAssign): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($value)
            ->setParameters([
                static::PRODUCT_BUNDLE_SKU_PARAMETER => $this->getMessageTransferParameter($this->productFacade->getProductConcreteSkusByConcreteIds([$idProductConcreteBundle])),
                static::PRODUCT_BUNDLED_SKUS_PARAMETER => $this->getMessageTransferParameter($this->productFacade->getProductConcreteSkusByConcreteIds($productForBundleIdsToAssign)),
            ]);
    }
}
