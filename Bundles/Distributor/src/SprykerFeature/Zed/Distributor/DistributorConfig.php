<?php

namespace SprykerFeature\Zed\Distributor;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

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
