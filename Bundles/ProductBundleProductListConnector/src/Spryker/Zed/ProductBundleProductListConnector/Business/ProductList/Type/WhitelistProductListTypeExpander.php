<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface;

class WhitelistProductListTypeExpander implements ProductListTypeExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * @param \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface $productBundleFacade
     */
    public function __construct(ProductBundleProductListConnectorToProductBundleFacadeInterface $productBundleFacade)
    {
        $this->productBundleFacade = $productBundleFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function expandProductBundle(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        foreach ($productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds() as $idProductConcrete) {
            $productListResponseTransfer = $this->whitelistExpandByIdProductConcrete($idProductConcrete, $productListResponseTransfer);
        }

        return $productListResponseTransfer;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function whitelistExpandByIdProductConcrete(int $idProductConcrete, ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        $productIdsToSave = $productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds();
        $productIdsToAdd = [];
        $bundledProducts = $this->productBundleFacade->findBundledProductsByIdProductConcrete($idProductConcrete);

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

        $messageTransfer = $this->createWhitelistMessageTransfer($idProductConcrete, $productIdsToAdd);
        $productListResponseTransfer->addMessage($messageTransfer);
        $productIdsToSave = array_merge($productIdsToSave, $productIdsToAdd);
        $productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->setProductIds($productIdsToSave);

        return $productListResponseTransfer;
    }

    /**
     * @param int $idProductBundle
     * @param int[] $idsProductConcrete
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createWhitelistMessageTransfer(int $idProductBundle, array $idsProductConcrete): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue('"id_product_bundle" ID was added to the whitelist with follow product IDs [ids_product_concrete]')
            ->setParameters([
                'id_product_bundle' => $idProductBundle,
                'ids_product_concrete' => implode(', ', $idsProductConcrete),
            ]);
    }
}
