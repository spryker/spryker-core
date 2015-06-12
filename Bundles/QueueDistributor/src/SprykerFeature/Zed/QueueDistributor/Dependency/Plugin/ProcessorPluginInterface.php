<?php

namespace SprykerFeature\Zed\QueueDistributor\Dependency\Plugin;

interface ProcessorPluginInterface
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
