<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Business;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method PriceCartConnectorBusinessFactory getFactory()
 */
class PriceCartConnectorFacade extends AbstractFacade
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     * @param null $grossPriceType
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    public function addGrossPriceToItems(ChangeTransfer $change, $grossPriceType = null)
    {
        return $this->getFactory()->createPriceManager($grossPriceType)->addGrossPriceToItems($change);
    }

}
