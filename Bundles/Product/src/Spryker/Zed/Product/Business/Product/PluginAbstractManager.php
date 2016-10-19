<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;

class PluginAbstractManager implements PluginAbstractManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected $beforeCreateCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected $afterCreateCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected $readCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected $beforeUpdateCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected $afterUpdateCollection;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[] $beforeCreateCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[] $afterCreateCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[] $readCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[] $beforeUpdateCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[] $afterUpdateCollection
     */
    public function __construct(
        array $beforeCreateCollection,
        array $afterCreateCollection,
        array $readCollection,
        array $beforeUpdateCollection,
        array $afterUpdateCollection
    ) {
        $this->beforeCreateCollection = $beforeCreateCollection;
        $this->afterCreateCollection = $afterCreateCollection;
        $this->readCollection = $readCollection;
        $this->beforeUpdateCollection = $beforeUpdateCollection;
        $this->afterUpdateCollection = $afterUpdateCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function triggerBeforeCreatePlugins(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->trigger(
            $productAbstractTransfer,
            $this->beforeCreateCollection
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function triggerAfterCreatePlugins(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->trigger(
            $productAbstractTransfer,
            $this->afterCreateCollection
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function triggerBeforeUpdatePlugins(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->trigger(
            $productAbstractTransfer,
            $this->beforeUpdateCollection
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function triggerAfterUpdatePlugins(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->trigger(
            $productAbstractTransfer,
            $this->afterUpdateCollection
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function triggerReadPlugins(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->trigger(
            $productAbstractTransfer,
            $this->readCollection
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $pluginCollection
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function trigger(ProductAbstractTransfer $productAbstractTransfer, array $pluginCollection)
    {
        foreach ($pluginCollection as $plugin) {
            $productAbstractTransfer = $plugin->run($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }

}
