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
     * @var \Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractExpanderPluginInterface[]
     */
    protected $productAbstractExpanderPlugins;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface[] $readCollection
     * @param \Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractExpanderPluginInterface[] $productAbstractExpanderPlugins
     */
    public function __construct(array $readCollection, array $productAbstractExpanderPlugins)
    {
        $this->readCollection = $readCollection;
        $this->productAbstractExpanderPlugins = $productAbstractExpanderPlugins;
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
        $productAbstractTransfer = $this->executeProductAbsractReadPlugins($productAbstractTransfer);
        $productAbstractTransfer = $this->executeProductAbstractExpanderPlugins($productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractReadObserverPluginManager::executeProductAbstractExpanderPlugins()} instead.
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function executeProductAbsractReadPlugins(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        foreach ($this->readCollection as $productAbstractPluginRead) {
            $productAbstractTransfer = $productAbstractPluginRead->read($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function executeProductAbstractExpanderPlugins(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        foreach ($this->productAbstractExpanderPlugins as $productAbstractExpanderPlugin) {
            $productAbstractTransfer = $productAbstractExpanderPlugin->expand($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }
}
