<?php

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Business\Processor;

/**
 * Class BreadcrumProcessor
 * @package SprykerFeature\Zed\ProductCategory\Business\Processor
 */
interface ProductCategoryBreadcrumbProcessorInterface
{
    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param $locale
     * @return array
     */
    public function process(array &$resultSet, array $processedResultSet, $locale);
}