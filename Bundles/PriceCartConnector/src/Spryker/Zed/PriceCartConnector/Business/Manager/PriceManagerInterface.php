<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;

interface PriceManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addGrossPriceToItems(CartChangeTransfer $change);

}
