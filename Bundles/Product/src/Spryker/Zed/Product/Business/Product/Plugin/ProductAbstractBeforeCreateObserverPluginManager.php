<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Product\Observer\ProductAbstractCreateObserverInterface;

/**
 * @deprecated Will be removed without replacement.
 */
class ProductAbstractBeforeCreateObserverPluginManager implements ProductAbstractCreateObserverInterface
{
    /**
     * @var array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface>
     */
    protected $beforeCreateCollection;

    /**
     * @param array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface> $beforeCreateCollection
     */
    public function __construct(array $beforeCreateCollection)
    {
        $this->beforeCreateCollection = $beforeCreateCollection;
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
        $productAbstractTransfer = $this->executeProductAbstractCreatePlugins($productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function executeProductAbstractCreatePlugins(
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        foreach ($this->beforeCreateCollection as $productAbstractPluginCreate) {
            $productAbstractTransfer = $productAbstractPluginCreate->create($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }
}
