<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;

class PluginConcreteManager implements PluginConcreteManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected $beforeCreateCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected $afterCreateCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected $readCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected $beforeUpdateCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected $afterUpdateCollection;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[] $beforeCreateCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[] $afterCreateCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[] $readCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[] $beforeUpdateCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[] $afterUpdateCollection
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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function triggerBeforeCreatePlugins(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->trigger(
            $productConcreteTransfer,
            $this->beforeCreateCollection
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function triggerAfterCreatePlugins(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->trigger(
            $productConcreteTransfer,
            $this->afterCreateCollection
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function triggerBeforeUpdatePlugins(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->trigger(
            $productConcreteTransfer,
            $this->beforeUpdateCollection
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function triggerAfterUpdatePlugins(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->trigger(
            $productConcreteTransfer,
            $this->afterUpdateCollection
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function triggerReadPlugins(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->trigger(
            $productConcreteTransfer,
            $this->readCollection
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array $pluginCollection
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function trigger(ProductConcreteTransfer $productConcreteTransfer, array $pluginCollection)
    {
        foreach ($pluginCollection as $plugin) {
            $productConcreteTransfer = $plugin->run($productConcreteTransfer);
        }

        return $productConcreteTransfer;
    }

}
