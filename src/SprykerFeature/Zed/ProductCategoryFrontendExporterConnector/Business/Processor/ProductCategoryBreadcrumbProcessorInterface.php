<?php

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Business\Processor;

/**
 * Class BreadcrumProcessor
 * @package SprykerFeature\Zed\ProductCategory\Business\Processor
 */
use SprykerEngine\Shared\Dto\LocaleDto;

interface ProductCategoryBreadcrumbProcessorInterface
{
    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleDto $locale
     * @return array
     */
    public function process(array &$resultSet, array $processedResultSet, LocaleDto $locale);
}
