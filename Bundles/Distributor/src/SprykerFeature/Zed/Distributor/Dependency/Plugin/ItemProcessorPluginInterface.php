<?php

namespace SprykerFeature\Zed\Distributor\Dependency\Plugin;

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
