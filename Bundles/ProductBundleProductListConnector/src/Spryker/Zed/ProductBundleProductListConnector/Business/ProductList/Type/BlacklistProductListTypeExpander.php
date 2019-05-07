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
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface;

class BlacklistProductListTypeExpander implements ProductListTypeExpanderInterface
{
    /**
     * @uses \Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap::COL_TYPE_BLACKLIST
     */
    protected const PRODUCT_LIST_TYPE_BLACKLIST = 'blacklist';

    protected const MESSAGE_PRODUCT_BUNDLE_SKU_WAS_BLACKLISTED = '%product_bundle_sku% was blacklisted because %product_for_bundle_skus% had been blacklisted.';
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
        return $productListTransfer->getType() === static::PRODUCT_LIST_TYPE_BLACKLIST;
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
            $messageTransfer = $this->generateMessageTransfer($productBundleTransfer->getIdProductConcreteBundle(), $idProductConcreteBundled);
            $productListResponseTransfer->addMessage($messageTransfer);
        }

        $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->setProductIds($productIdsToAssign);

        return $productListResponseTransfer;
    }

    /**
     * @param int $idProductConcreteBundle
     * @param int $idProductConcreteBundled
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function generateMessageTransfer(int $idProductConcreteBundle, int $idProductConcreteBundled): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::MESSAGE_PRODUCT_BUNDLE_SKU_WAS_BLACKLISTED)
            ->setParameters([
                static::PRODUCT_BUNDLE_SKU_PARAMETER => $this->getMessageTransferSkuParameterByIds([$idProductConcreteBundle]),
                static::PRODUCT_FOR_BUNDLE_SKUS_PARAMETER => $this->getMessageTransferSkuParameterByIds([$idProductConcreteBundled]),
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
