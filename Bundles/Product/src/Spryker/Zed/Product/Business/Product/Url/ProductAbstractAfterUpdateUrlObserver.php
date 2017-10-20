<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Url;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface;
use Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface;

class ProductAbstractAfterUpdateUrlObserver implements ProductAbstractUpdateObserverInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface
     */
    protected $productAbstractStatusChecker;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface
     */
    protected $productUrlManager;

    /**
     * @param \Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface $productAbstractStatusChecker
     * @param \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface $productUrlManager
     */
    public function __construct(ProductAbstractStatusCheckerInterface $productAbstractStatusChecker, ProductUrlManagerInterface $productUrlManager)
    {
        $this->productAbstractStatusChecker = $productAbstractStatusChecker;
        $this->productUrlManager = $productUrlManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function update(ProductAbstractTransfer $productAbstractTransfer)
    {
        if ($this->productAbstractStatusChecker->isActive($productAbstractTransfer->getIdProductAbstract())) {
            $this->productUrlManager->updateProductUrl($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }
}
