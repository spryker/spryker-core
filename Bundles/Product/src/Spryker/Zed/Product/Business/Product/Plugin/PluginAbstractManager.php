<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;

class PluginAbstractManager implements PluginAbstractManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[]
     */
    protected $beforeCreateCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[]
     */
    protected $afterCreateCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface[]
     */
    protected $readCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[]
     */
    protected $beforeUpdateCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[]
     */
    protected $afterUpdateCollection;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[] $beforeCreateCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[] $afterCreateCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface[] $readCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[] $beforeUpdateCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[] $afterUpdateCollection
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
        foreach ($this->beforeCreateCollection as $plugin) {
            $productAbstractTransfer = $plugin->create($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function triggerAfterCreatePlugins(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($this->afterCreateCollection as $plugin) {
            $productAbstractTransfer = $plugin->create($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function triggerBeforeUpdatePlugins(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($this->beforeUpdateCollection as $plugin) {
            $productAbstractTransfer = $plugin->update($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function triggerAfterUpdatePlugins(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($this->afterUpdateCollection as $plugin) {
            $productAbstractTransfer = $plugin->update($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function triggerReadPlugins(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($this->readCollection as $plugin) {
            $productAbstractTransfer = $plugin->read($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }

}
