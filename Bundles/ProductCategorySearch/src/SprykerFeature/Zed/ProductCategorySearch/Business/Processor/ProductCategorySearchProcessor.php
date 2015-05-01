<?php

namespace SprykerFeature\Zed\ProductCategorySearch\Business\Processor;

use SprykerEngine\Shared\Dto\LocaleDto;

class ProductCategorySearchProcessor implements ProductCategorySearchProcessorInterface
{

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleDto $locale
     * @return array
     */
    public function process(array &$resultSet, array $processedResultSet, LocaleDto $locale)
    {
        foreach ($resultSet as $index => $productCategories) {
            if (isset($processedResultSet[$index])) {
                $processedResultSet[$index]['category'] = [
                    'direct-parents' => explode(',', $productCategories['node_id']),
                    'all-parents' => explode(',', $productCategories['category_parent_ids']),
                ];
            }
        }

        return $processedResultSet;
    }
}
