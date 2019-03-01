<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use Generated\Shared\Transfer\ProductListResponseTransfer;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface;

class WhitelistProductListTypeExpander implements ProductListTypeExpanderInterface
{
    protected const MESSAGE_VALUE = ' was added to the whitelist with follow products ';

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListMessageGeneratorInterface
     */
    protected $productListMessageGenerator;

    /**
     * @param \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface $productBundleFacade
     * @param \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListMessageGeneratorInterface $productListMessageGenerator
     */
    public function __construct(
        ProductBundleProductListConnectorToProductBundleFacadeInterface $productBundleFacade,
        ProductListMessageGeneratorInterface $productListMessageGenerator
    ) {
        $this->productBundleFacade = $productBundleFacade;
        $this->productListMessageGenerator = $productListMessageGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function expandProductBundle(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        foreach ($productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds() as $idProductConcrete) {
            $productListResponseTransfer = $this->whitelistExpandByIdProductConcreteBundle($idProductConcrete, $productListResponseTransfer);
        }

        return $productListResponseTransfer;
    }

    /**
     * @param int $idProductConcreteBundle
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function whitelistExpandByIdProductConcreteBundle(int $idProductConcreteBundle, ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        $productIdsToSave = $productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds();
        $productIdsToAdd = [];
        $bundledProducts = $this->productBundleFacade->findBundledProductsByIdProductConcrete($idProductConcreteBundle);

        if (!$bundledProducts->count()) {
            return $productListResponseTransfer;
        }

        foreach ($bundledProducts as $bundledProduct) {
            if (in_array($bundledProduct->getIdProductConcrete(), $productIdsToSave)) {
                break;
            }
            $productIdsToAdd[] = $bundledProduct->getIdProductConcrete();
        }

        if (!$productIdsToAdd) {
            return $productListResponseTransfer;
        }

        $messageTransfer = $this->productListMessageGenerator
            ->generateMessageTransfer(static::MESSAGE_VALUE, $idProductConcreteBundle, $productIdsToAdd);
        $productIdsToSave = array_merge($productIdsToSave, $productIdsToAdd);

        $productListResponseTransfer->addMessage($messageTransfer);
        $productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->setProductIds($productIdsToSave);

        return $productListResponseTransfer;
    }
}
