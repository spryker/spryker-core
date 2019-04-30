<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface;

class WhitelistProductListTypeExpander implements ProductListTypeExpanderInterface
{
    /**
     * @uses \Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap::COL_TYPE_WHITELIST
     */
    protected const PRODUCT_LIST_TYPE_WHITELIST = 'whitelist';

    protected const MESSAGE_PRODUCT_BUNDLE_SKU_WAS_ADDED_TO_THE_WHITELIST = '%product_bundle_sku% was added to the whitelist with the following products %product_for_bundle_skus%.';
    protected const PRODUCT_BUNDLE_SKU_PARAMETER = '%product_bundle_sku%';
    protected const PRODUCT_FOR_BUNDLE_SKUS_PARAMETER = '%product_for_bundle_skus%';

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface $productBundleFacade
     * @param \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductBundleProductListConnectorToProductBundleFacadeInterface $productBundleFacade,
        ProductBundleProductListConnectorToProductFacadeInterface $productFacade
    ) {
        $this->productBundleFacade = $productBundleFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductListTransfer $productListTransfer): bool
    {
        return $productListTransfer->getType() === static::PRODUCT_LIST_TYPE_WHITELIST;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function expand(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        foreach ($productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds() as $idProductConcrete) {
            $productListResponseTransfer = $this->expandProductListByIdProductConcrete($idProductConcrete, $productListResponseTransfer);
        }

        return $productListResponseTransfer;
    }

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

        $messageTransfer = $this->generateMessageTransfer($idProductConcreteBundle, $productForBundleIdsToAssign);
        $productListResponseTransfer->addMessage($messageTransfer);
        $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->setProductIds(array_merge($productIdsToAssign, $productForBundleIdsToAssign));

        return $productListResponseTransfer;
    }

    /**
     * @param int $idProductConcreteBundle
     * @param int[] $productForBundleIdsToAssign
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function generateMessageTransfer(int $idProductConcreteBundle, array $productForBundleIdsToAssign): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::MESSAGE_PRODUCT_BUNDLE_SKU_WAS_ADDED_TO_THE_WHITELIST)
            ->setParameters([
                static::PRODUCT_BUNDLE_SKU_PARAMETER => $this->getMessageTransferSkuParameterByIds([$idProductConcreteBundle]),
                static::PRODUCT_FOR_BUNDLE_SKUS_PARAMETER => $this->getMessageTransferSkuParameterByIds($productForBundleIdsToAssign),
            ]);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return string
     */
    protected function getMessageTransferSkuParameterByIds(array $productConcreteIds): string
    {
        $productConcreteSkus = $this->productFacade->getProductConcreteSkusByConcreteIds($productConcreteIds);

        return implode(', ', array_keys($productConcreteSkus));
    }
}
