<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface;

class BlacklistProductListTypeExpander implements ProductListTypeExpanderInterface
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
        $productBundleCollectionTransfer = $this->productBundleFacade->findProductBundleCollectionByAssignedIdProductConcrete($idProductConcrete);

        if (!$productBundleCollectionTransfer->getProductBundles()->count()) {
            return $productListResponseTransfer;
        }

        foreach ($productBundleCollectionTransfer->getProductBundles() as $productBundle) {
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
}
