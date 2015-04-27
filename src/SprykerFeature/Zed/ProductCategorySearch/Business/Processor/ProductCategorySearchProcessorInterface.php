<?php

namespace SprykerFeature\Zed\ProductCategorySearch\Business\Processor;

/**
 * Class ProductCategoryProcessor
 * @package SprykerFeature\Zed\ProductCategory\Business\Processor
 */
use SprykerEngine\Shared\Dto\LocaleDto;

interface ProductCategorySearchProcessorInterface
{
    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function process(array &$resultSet, array $processedResultSet, LocaleDto $locale);
}
