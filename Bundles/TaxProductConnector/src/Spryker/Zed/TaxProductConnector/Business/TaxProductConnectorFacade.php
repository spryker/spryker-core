<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\TaxProductConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\TaxProductConnector\Business\Plugin\TaxChangeTouchPlugin;

/**
 * @method TaxProductConnectorBusinessFactory getFactory()
 */
class TaxProductConnectorFacade extends AbstractFacade
{

    /**
     * @return \Spryker\Zed\TaxProductConnector\Business\Plugin\TaxChangeTouchPlugin
     */
    public function getTaxChangeTouchPlugin()
    {
        return $this->getFactory()->createTaxChangeTouchPlugin();
    }

}
