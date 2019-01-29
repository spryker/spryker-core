<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\Expander;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductListMapTransfer;
use Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface;

class ProductConcretePageSearchExpander implements ProductConcretePageSearchExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface
     */
    protected $productListReader;

    /**
     * @param \Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface $productListReader
     */
    public function __construct(ProductListReaderInterface $productListReader)
    {
        $this->productListReader = $productListReader;
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
        $whitelists = $this->productListReader->getProductWhitelistIdsByIdProduct($productConcretePageSearchTransfer->getFkProduct());

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
        $blacklists = $this->productListReader->getProductBlacklistIdsByIdProduct($productConcretePageSearchTransfer->getFkProduct());

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
