<?php

namespace SprykerFeature\Zed\QueueDistributor\Dependency\Plugin;

interface ItemProcessorPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType();

    /**
     * @param array $processableItem
     *
     * @return array
     */
    public function processItem(array $processableItem);
}
