<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;

interface PriceManagerInterface
{

    /**
     * @param CartChangeTransfer $change
     *
     * @return CartChangeTransfer
     */
    public function addGrossPriceToItems(CartChangeTransfer $change);

}
