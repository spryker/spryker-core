<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Product\Observer\ProductAbstractReadObserverInterface;

class ProductAbstractReadObserverPluginManager implements ProductAbstractReadObserverInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface[]
     */
    protected $readCollection;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface[] $readCollection
     */
    public function __construct(array $readCollection)
    {
        $this->readCollection = $readCollection;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function read(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($this->readCollection as $productAbstractPluginRead) {
            $productAbstractTransfer = $productAbstractPluginRead->read($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }
}
