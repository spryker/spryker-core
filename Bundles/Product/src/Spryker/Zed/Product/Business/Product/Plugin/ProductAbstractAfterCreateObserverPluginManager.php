<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Product\Observer\ProductAbstractCreateObserverInterface;

class ProductAbstractAfterCreateObserverPluginManager implements ProductAbstractCreateObserverInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[]
     */
    protected $afterCreateCollection;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[] $afterCreateCollection
     */
    public function __construct(array $afterCreateCollection)
    {
        $this->afterCreateCollection = $afterCreateCollection;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function create(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($this->afterCreateCollection as $productAbstractPluginCreate) {
            $productAbstractTransfer = $productAbstractPluginCreate->create($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }
}
