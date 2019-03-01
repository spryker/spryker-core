<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use Generated\Shared\Transfer\ProductListResponseTransfer;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface;

class BlacklistProductListTypeExpander implements ProductListTypeExpanderInterface
{
    protected const MESSAGE_VALUE = ' was blacklisted due to blacklisting ';

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
        foreach ($productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds() as $idProductConcreteAssigned) {
            $productListResponseTransfer = $this->blacklistExpandByIdProductConcrete($idProductConcreteAssigned, $productListResponseTransfer);
        }

        return $productListResponseTransfer;
    }

    /**
     * @param int $idProductConcreteAssigned
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function blacklistExpandByIdProductConcrete(int $idProductConcreteAssigned, ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        $productIdsToSave = $productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds();
        $productBundleCollectionTransfer = $this->productBundleFacade->findProductBundleCollectionByAssignedIdProductConcrete($idProductConcreteAssigned);

        if (!$productBundleCollectionTransfer->getProductBundles()->count()) {
            return $productListResponseTransfer;
        }

        foreach ($productBundleCollectionTransfer->getProductBundles() as $productBundle) {
            if (in_array($productBundle->getIdProductConcrete(), $productIdsToSave)) {
                break;
            }

            $productIdsToSave[] = $productBundle->getIdProductConcrete();
            $messageTransfer = $this->productListMessageGenerator
                ->generateMessageTransfer(static::MESSAGE_VALUE, $productBundle->getIdProductConcrete(), [$idProductConcreteAssigned]);
            $productListResponseTransfer->addMessage($messageTransfer);
        }

        $productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->setProductIds($productIdsToSave);

        return $productListResponseTransfer;
    }
}
