<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxProductConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\TaxProductConnector\Business\Plugin\TaxChangeTouchPlugin;

/**
 * @method TaxProductConnectorDependencyContainer getDependencyContainer()
 */
class TaxProductConnectorFacade extends AbstractFacade
{

    /**
     * @return TaxChangeTouchPlugin
     */
    public function getTaxChangeTouchPlugin()
    {
        return $this->getDependencyContainer()->getTaxChangeTouchPlugin();
    }

}
