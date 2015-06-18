<?php

namespace SprykerFeature\Zed\QueueDistributor\Dependency\Plugin;

interface ConsumerProviderPluginInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @param array $item
     *
     * @return array
     */
    public function getConsumerList(array $item = []);
}
