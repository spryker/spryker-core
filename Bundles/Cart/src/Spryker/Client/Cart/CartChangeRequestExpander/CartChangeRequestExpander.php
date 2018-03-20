<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\CartChangeRequestExpander;

use Generated\Shared\Transfer\CartChangeTransfer;

class CartChangeRequestExpander implements CartChangeRequestExpanderInterface
{
    /**
     * @var array|\Spryker\Client\Cart\Dependency\Plugin\CartChangeRequestExpanderPluginInterface[]
     */
    protected $changeQuantityRequestExpanderPlugins;

    /**
     * @var array|\Spryker\Client\Cart\Dependency\Plugin\CartChangeRequestExpanderPluginInterface[]
     */
    protected $addItemRequestExpanderPlugins;

    /**
     * @var array|\Spryker\Client\Cart\Dependency\Plugin\CartChangeRequestExpanderPluginInterface[]
     */
    protected $removeItemRequestExpanderPlugins;

    /**
     * @param \Spryker\Client\Cart\Dependency\Plugin\CartChangeRequestExpanderPluginInterface[] $addItemsRequestExpanderPlugins
     * @param \Spryker\Client\Cart\Dependency\Plugin\CartChangeRequestExpanderPluginInterface[] $removeItemRequestExpanderPlugins
     * @param \Spryker\Client\Cart\Dependency\Plugin\CartChangeRequestExpanderPluginInterface[] $changeQuantityRequestExpanderPlugins
     */
    public function __construct(
        array $addItemsRequestExpanderPlugins,
        array $removeItemRequestExpanderPlugins,
        array $changeQuantityRequestExpanderPlugins
    ) {
        $this->addItemRequestExpanderPlugins = $addItemsRequestExpanderPlugins;
        $this->removeItemRequestExpanderPlugins = $removeItemRequestExpanderPlugins;
        $this->changeQuantityRequestExpanderPlugins = $changeQuantityRequestExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addItemsRequestExpand(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        foreach ($this->addItemRequestExpanderPlugins as $changeRequestExpanderPlugin) {
            $cartChangeTransfer = $changeRequestExpanderPlugin->expand($cartChangeTransfer, $params);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function changeQuantityRequestExpand(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        foreach ($this->changeQuantityRequestExpanderPlugins as $changeRequestExpanderPlugin) {
            $cartChangeTransfer = $changeRequestExpanderPlugin->expand($cartChangeTransfer, $params);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function removeItemRequestExpand(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        foreach ($this->removeItemRequestExpanderPlugins as $changeRequestExpanderPlugin) {
            $cartChangeTransfer = $changeRequestExpanderPlugin->expand($cartChangeTransfer, $params);
        }

        return $cartChangeTransfer;
    }
}
