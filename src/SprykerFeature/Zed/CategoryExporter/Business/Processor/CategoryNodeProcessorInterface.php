<?php

namespace SprykerFeature\Zed\CategoryExporter\Business\Processor;

/**
 * Class CategoryNodeProcessor
 *
 * @package SprykerFeature\Zed\CategoryExporter\Business\Processor
 */
interface CategoryNodeProcessorInterface
{
    /**
     * @param array $categoryNodes
     * @param $locale
     * @return mixed
     */
    public function process(array $categoryNodes, $locale);
}