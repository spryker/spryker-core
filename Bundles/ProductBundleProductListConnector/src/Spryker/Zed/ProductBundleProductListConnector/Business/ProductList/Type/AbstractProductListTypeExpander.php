<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface;

abstract class AbstractProductListTypeExpander implements ProductListTypeExpanderInterface
{
    protected const PRODUCT_BUNDLE_SKU_PARAMETER = 'product_bundle_sku';
    protected const PRODUCT_FOR_BUNDLE_SKUS_PARAMETER = 'product_for_bundle_skus';

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    abstract protected function expandProductListByIdProductConcrete(int $idProductConcrete, ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer;

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
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function expandProductListWithProductBundle(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        foreach ($productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds() as $idProductConcrete) {
            $productListResponseTransfer = $this->expandProductListByIdProductConcrete($idProductConcrete, $productListResponseTransfer);
        }

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
