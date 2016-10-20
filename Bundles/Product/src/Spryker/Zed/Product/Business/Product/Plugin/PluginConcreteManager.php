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
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface[]
     */
    protected $beforeCreateCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface[]
     */
    protected $afterCreateCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface[]
     */
    protected $readCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[]
     */
    protected $beforeUpdateCollection;

    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[]
     */
    protected $afterUpdateCollection;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface[] $beforeCreateCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface[] $afterCreateCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface[] $readCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[] $beforeUpdateCollection
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[] $afterUpdateCollection
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
        foreach ($this->beforeCreateCollection as $plugin) {
            $productConcreteTransfer = $plugin->create($productConcreteTransfer);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function triggerAfterCreatePlugins(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($this->afterCreateCollection as $plugin) {
            $productConcreteTransfer = $plugin->create($productConcreteTransfer);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function triggerBeforeUpdatePlugins(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($this->afterUpdateCollection as $plugin) {
            $productConcreteTransfer = $plugin->update($productConcreteTransfer);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function triggerAfterUpdatePlugins(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($this->afterUpdateCollection as $plugin) {
            $productConcreteTransfer = $plugin->update($productConcreteTransfer);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function triggerReadPlugins(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($this->readCollection as $plugin) {
            $productConcreteTransfer = $plugin->read($productConcreteTransfer);
        }

        return $productConcreteTransfer;
    }

}
