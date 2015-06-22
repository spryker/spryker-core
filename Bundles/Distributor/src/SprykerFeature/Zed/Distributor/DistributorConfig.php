<?php

namespace SprykerFeature\Zed\Distributor;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Zed\Distributor\Dependency\Plugin\ItemProcessorPluginInterface;
use SprykerFeature\Zed\Distributor\Dependency\Plugin\QueryExpanderPluginInterface;

class DistributorConfig extends AbstractBundleConfig
{

    /**
     * @return ItemProcessorPluginInterface[]
     */
    public function getItemProcessors()
    {
        return [];
    }

    /**
     * @return QueryExpanderPluginInterface[]
     */
    public function getQueryExpander()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAvailableItemTypes()
    {
        return [];
    }
}
