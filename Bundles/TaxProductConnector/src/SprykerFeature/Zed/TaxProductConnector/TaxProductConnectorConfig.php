<?php

namespace SprykerFeature\Zed\TaxProductConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface;

class TaxProductConnectorConfig extends AbstractBundleConfig
{
    /**
     * @return TaxChangePluginInterface[]
     */
    public function getTaxChangePlugins()
    {
        return [];
    }
}
