<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Dependency;

use Generated\Shared\Transfer\CartChangeTransfer;

interface ItemExpanderPluginInterface
{

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer);

}
