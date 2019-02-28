<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList;

use Generated\Shared\Transfer\ProductListResponseTransfer;
use Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface;
use Spryker\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorConfig;

class ProductListExpander implements ProductListExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorConfig
     */
    protected $productBundleProductListConnectorConfig;

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface
     */
    protected $blacklistProductListTypeExpander;

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface
     */
    protected $whitelistProductListTypeExpander;

    /**
     * @param \Spryker\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorConfig $productBundleProductListConnectorConfig
     * @param \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface $blacklistProductListTypeExpander
     * @param \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface $whitelistProductListTypeExpander
     */
    public function __construct(
        ProductBundleProductListConnectorConfig $productBundleProductListConnectorConfig,
        ProductListTypeExpanderInterface $blacklistProductListTypeExpander,
        ProductListTypeExpanderInterface $whitelistProductListTypeExpander
    ) {
        $this->productBundleProductListConnectorConfig = $productBundleProductListConnectorConfig;
        $this->blacklistProductListTypeExpander = $blacklistProductListTypeExpander;
        $this->whitelistProductListTypeExpander = $whitelistProductListTypeExpander;
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
            return $this->blacklistProductListTypeExpander->expandProductBundle($productListResponseTransfer);
        }

        return $this->whitelistProductListTypeExpander->expandProductBundle($productListResponseTransfer);
    }
}
