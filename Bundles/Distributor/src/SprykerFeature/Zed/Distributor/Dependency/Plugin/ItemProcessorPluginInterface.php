<?php

namespace SprykerFeature\Zed\Distributor\Dependency\Plugin;

interface ItemProcessorPluginInterface
{

    /**
     * @return string
     */
    public function getProcessableType();

    /**
     * @param array $processableItems
     * @param array $resultSet
     *
     * @return array
     */
    public function processItems(array $processableItems, array &$resultSet);

}
