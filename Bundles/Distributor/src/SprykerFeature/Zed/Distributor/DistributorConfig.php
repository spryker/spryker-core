<?php

namespace SprykerFeature\Zed\Distributor;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Zed\Distributor\Dependency\Plugin\ItemProcessorPluginInterface;
use SprykerFeature\Zed\Distributor\Dependency\Plugin\DistributorQueryExpanderPluginInterface;

class DistributorConfig extends AbstractBundleConfig
{

    /**
     * @return array
     */
    public function getItemTypes()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getItemReceivers()
    {
        return [];
    }
}
