<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Product\Observer\ProductAbstractCreateObserverInterface;

class ProductAbstractBeforeCreateObserverPluginManager implements ProductAbstractCreateObserverInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[]
     */
    protected $beforeCreateCollection;

    /**
     * @var array|\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPreCreatePluginInterface[]
     */
    protected $productAbstractPreCreatePlugins;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[] $beforeCreateCollection
     * @param \Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPreCreatePluginInterface[] $productAbstractPreCreatePlugins
     */
    public function __construct(
        array $beforeCreateCollection,
        array $productAbstractPreCreatePlugins
    ) {
        $this->beforeCreateCollection = $beforeCreateCollection;
        $this->productAbstractPreCreatePlugins = $productAbstractPreCreatePlugins;
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
        $productAbstractTransfer = $this->executeProductAbstractPreCreatePlugins($productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractBeforeCreateObserverPluginManager::executeProductAbstractPreCreatePlugins()} instead.
     *
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

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function executeProductAbstractPreCreatePlugins(
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        foreach ($this->productAbstractPreCreatePlugins as $productAbstractPreCreatePlugin) {
            $productAbstractTransfer = $productAbstractPreCreatePlugin->create($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }
}
