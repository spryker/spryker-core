<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\TaxProductConnector\Business;

interface TaxProductConnectorFacadeInterface
{

    /**
     * @return \Spryker\Zed\TaxProductConnector\Business\Plugin\TaxChangeTouchPlugin
     */
    public function getTaxChangeTouchPlugin();

}
