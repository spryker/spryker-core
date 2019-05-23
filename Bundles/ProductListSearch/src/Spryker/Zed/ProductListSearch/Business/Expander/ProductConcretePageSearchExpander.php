<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Business\Expander;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductListMapTransfer;
use Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductListFacadeInterface;

class ProductConcretePageSearchExpander implements ProductConcretePageSearchExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @param \Spryker\Zed\ProductListSearch\Dependency\Facade\ProductListSearchToProductListFacadeInterface $productListFacade
     */
    public function __construct(ProductListSearchToProductListFacadeInterface $productListFacade)
    {
        $this->productListFacade = $productListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function expandProductConcretePageSearchTransferWithProductLists(
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
    ): ProductConcretePageSearchTransfer {
        $productConcretePageSearchTransfer->requireFkProduct();

        $productConcretePageSearchTransfer = $this->sanitizeProductConcretePageSearchTransfer($productConcretePageSearchTransfer);
        $productConcretePageSearchTransfer = $this->expandProductConcretePageSearchTransferWithWhitelistIds($productConcretePageSearchTransfer);
        $productConcretePageSearchTransfer = $this->expandProductConcretePageSearchTransferWithBlacklistIds($productConcretePageSearchTransfer);

        return $productConcretePageSearchTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    protected function expandProductConcretePageSearchTransferWithWhitelistIds(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): ProductConcretePageSearchTransfer
    {
        $whitelists = $this->productListFacade->getProductWhitelistIdsByIdProduct($productConcretePageSearchTransfer->getFkProduct());

        if ($whitelists) {
            $productConcretePageSearchTransfer->getProductListMap()->setWhitelists($whitelists);
        }

        return $productConcretePageSearchTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    protected function expandProductConcretePageSearchTransferWithBlacklistIds(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): ProductConcretePageSearchTransfer
    {
        $blacklists = $this->productListFacade->getProductBlacklistIdsByIdProduct($productConcretePageSearchTransfer->getFkProduct());

        if ($blacklists) {
            $productConcretePageSearchTransfer->getProductListMap()->setBlacklists($blacklists);
        }

        return $productConcretePageSearchTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    protected function sanitizeProductConcretePageSearchTransfer(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): ProductConcretePageSearchTransfer
    {
        if (!$productConcretePageSearchTransfer->getProductListMap()) {
            $productConcretePageSearchTransfer->setProductListMap(new ProductListMapTransfer());
        }

        return $productConcretePageSearchTransfer;
    }
}
