<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList;

use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface;

class ProductListExpander implements ProductListExpanderInterface
{
    /**
     * @uses \Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap::COL_TYPE_BLACKLIST
     */
    protected const PRODUCT_LIST_TYPE_BLACKLIST = 'blacklist';

    /**
     * @uses \Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap::COL_TYPE_WHITELIST
     */
    protected const PRODUCT_LIST_TYPE_WHITELIST = 'whitelist';

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface
     */
    protected $blacklistProductListTypeExpander;

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface
     */
    protected $whitelistProductListTypeExpander;

    /**
     * @param \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface $blacklistProductListTypeExpander
     * @param \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface $whitelistProductListTypeExpander
     */
    public function __construct(
        ProductListTypeExpanderInterface $blacklistProductListTypeExpander,
        ProductListTypeExpanderInterface $whitelistProductListTypeExpander
    ) {
        $this->blacklistProductListTypeExpander = $blacklistProductListTypeExpander;
        $this->whitelistProductListTypeExpander = $whitelistProductListTypeExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function expandProductListWithProductBundle(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        $productListResponseTransfer = (new ProductListResponseTransfer())
            ->setProductList($productListTransfer);

        if ($productListTransfer->getType() === null) {
            return $productListResponseTransfer;
        }

        if ($productListTransfer->getType() === static::PRODUCT_LIST_TYPE_BLACKLIST) {
            return $this->blacklistProductListTypeExpander->expandProductListWithProductBundle($productListResponseTransfer);
        }

        if ($productListTransfer->getType() === static::PRODUCT_LIST_TYPE_WHITELIST) {
            return $this->whitelistProductListTypeExpander->expandProductListWithProductBundle($productListResponseTransfer);
        }

        return $productListResponseTransfer;
    }
}
