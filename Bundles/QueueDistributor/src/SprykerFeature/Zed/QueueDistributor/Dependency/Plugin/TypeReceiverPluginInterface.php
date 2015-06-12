<?php

namespace SprykerFeature\Zed\QueueDistributor\Dependency\Plugin;

interface TypeReceiverPluginInterface
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
    public function getReceiverList(array $item = []);
}
