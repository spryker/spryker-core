<?php

namespace SprykerFeature\Zed\GlossaryDistributor\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Distributor\Dependency\Plugin\ItemProcessorPluginInterface;

class GlossaryItemProcessorPlugin extends AbstractPlugin implements
    ItemProcessorPluginInterface
{

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'glossary_translation';
    }

    /**
     * @param array $processableItem
     *
     * @return array
     */
    public function processItem(array $processableItem)
    {
        return $processableItem;
    }
}
