<?php

namespace SprykerFeature\Zed\CategoryExporter\Business\Processor;

/**
 * Class NavigationProcessor
 *
 * @package SprykerFeature\Zed\CategoryExporter\Business\Processor
 */
interface NavigationProcessorInterface
{

    /**
     * @param array $categoryNodes
     * @param string $locale
     * @return array
     */
    public function process(array $categoryNodes, $locale);
}
