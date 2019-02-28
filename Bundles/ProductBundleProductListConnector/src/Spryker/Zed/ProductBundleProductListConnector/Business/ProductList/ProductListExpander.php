<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorConfig;

class ProductListExpander implements ProductListExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorConfig
     */
    protected $productBundleProductListConnectorConfig;

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * @param \Spryker\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorConfig $productBundleProductListConnectorConfig
     * @param \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface $productBundleFacade
     */
    public function __construct(
        ProductBundleProductListConnectorConfig $productBundleProductListConnectorConfig,
        ProductBundleProductListConnectorToProductBundleFacadeInterface $productBundleFacade
    ) {
        $this->productBundleProductListConnectorConfig = $productBundleProductListConnectorConfig;
        $this->productBundleFacade = $productBundleFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function expandProductBundle(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        if (!$productListResponseTransfer->getProductList() || !$productListResponseTransfer->getProductList()->getType()) {
            return $productListResponseTransfer;
        }

        if ($productListResponseTransfer->getProductList()->getType() === $this->productBundleProductListConnectorConfig->getProductListTypeBlacklist()) {
            return $this->blacklistExpand($productListResponseTransfer);
        }

        return $this->whitelistExpand($productListResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function blacklistExpand(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        foreach ($productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds() as $idProductConcrete) {
            $productListResponseTransfer = $this->blacklistExpandByIdProduct($idProductConcrete, $productListResponseTransfer);
        }

        return $productListResponseTransfer;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function blacklistExpandByIdProduct(int $idProductConcrete, ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        $productIdsToSave = $productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds();
        $productBundleCollection = $this->productBundleFacade->findProductBundleCollectionByAssignedIdProductConcrete($idProductConcrete);

        if (!count($productBundleCollection->toArray())) {
            return $productListResponseTransfer;
        }

        foreach ($productBundleCollection->getProductBundles() as $productBundle) {
            if (in_array($productBundle->getIdProductConcrete(), $productIdsToSave)) {
                break;
            }

            $productIdsToSave[] = $productBundle->getIdProductConcrete();
            $messageTransfer = $this->createBlacklistMessageTransfer($productBundle->getIdProductConcrete(), $idProductConcrete);

            $productListResponseTransfer->addMessage($messageTransfer);
        }

        $productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->setProductIds($productIdsToSave);

        return $productListResponseTransfer;
    }

    /**
     * @param int $idProductBundle
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createBlacklistMessageTransfer(int $idProductBundle, int $idProductConcrete): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue('"id_product_bundle" ID was blacklisted due to blacklisting "id_product_concrete" ID')
            ->setParameters([
                'id_product_bundle' => $idProductBundle,
                'id_product_concrete' => $idProductConcrete,
            ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function whitelistExpand(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        foreach ($productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds() as $idProductConcrete) {
            $productListResponseTransfer = $this->whitelistExpandByIdProduct($idProductConcrete, $productListResponseTransfer);
        }

        return $productListResponseTransfer;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function whitelistExpandByIdProduct(int $idProductConcrete, ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
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
