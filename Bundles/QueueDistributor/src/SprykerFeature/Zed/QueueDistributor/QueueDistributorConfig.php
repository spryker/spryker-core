<?php

namespace SprykerFeature\Zed\QueueDistributor;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Zed\QueueDistributor\Dependency\Plugin\ItemProcessorPluginInterface;
use SprykerFeature\Zed\QueueDistributor\Dependency\Plugin\QueryExpanderPluginInterface;

class QueueDistributorConfig extends AbstractBundleConfig
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
}
