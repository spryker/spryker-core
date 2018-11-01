<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;

class CartChangeRequestExpander implements CartChangeRequestExpanderInterface
{
    /**
     * @var \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\CartChangeRequestExpandPluginInterface[]
     */
    protected $removeItemRequestExpanderPlugins;

    /**
     * @param \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\CartChangeRequestExpandPluginInterface[] $removeItemRequestExpanderPlugins
     */
    public function __construct(array $removeItemRequestExpanderPlugins)
    {
        $this->removeItemRequestExpanderPlugins = $removeItemRequestExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function removeItemRequestExpand(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($this->removeItemRequestExpanderPlugins as $changeRequestExpanderPlugin) {
            $cartChangeTransfer = $changeRequestExpanderPlugin->expand($cartChangeTransfer);
        }

        return $cartChangeTransfer;
    }
}
