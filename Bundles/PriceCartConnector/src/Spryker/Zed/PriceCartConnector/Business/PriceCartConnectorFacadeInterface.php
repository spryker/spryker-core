<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Business;

use Generated\Shared\Transfer\ChangeTransfer;

interface PriceCartConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     * @param null $grossPriceType
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    public function addGrossPriceToItems(ChangeTransfer $change, $grossPriceType = null);

}
