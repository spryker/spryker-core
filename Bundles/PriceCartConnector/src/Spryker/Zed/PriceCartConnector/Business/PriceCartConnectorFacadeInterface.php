<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;

interface PriceCartConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $change
     * @param null $grossPriceType
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addGrossPriceToItems(CartChangeTransfer $change, $grossPriceType = null);

}
