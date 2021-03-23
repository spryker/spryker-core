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
     * @var array|\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPostCreatePluginInterface[]
     */
    protected $productAbstractPostCreatePlugins;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[] $afterCreateCollection
     * @param \Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPostCreatePluginInterface[] $productAbstractPostCreatePlugins
     */
    public function __construct(
        array $afterCreateCollection,
        array $productAbstractPostCreatePlugins
    ) {
        $this->afterCreateCollection = $afterCreateCollection;
        $this->productAbstractPostCreatePlugins = $productAbstractPostCreatePlugins;
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
        $productAbstractTransfer = $this->executeProductAbstractPostCreatePlugins($productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractAfterCreateObserverPluginManager::executeProductAbstractPostCreatePlugins()} instead.
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function executeProductAbstractCreatePlugins(
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        foreach ($this->afterCreateCollection as $productAbstractPluginCreate) {
            $productAbstractTransfer = $productAbstractPluginCreate->create($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function executeProductAbstractPostCreatePlugins(
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        foreach ($this->productAbstractPostCreatePlugins as $productAbstractPostCreatePlugin) {
            $productAbstractTransfer = $productAbstractPostCreatePlugin->create($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }
}
