<?php

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Business\Processor;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;

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
