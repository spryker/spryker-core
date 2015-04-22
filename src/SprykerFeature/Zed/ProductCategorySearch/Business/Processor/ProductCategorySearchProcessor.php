<?php

namespace SprykerFeature\Zed\ProductCategorySearch\Business\Processor;

/**
 * Class ProductCategoryProcessor
 * @package SprykerFeature\Zed\ProductCategory\Business\Processor
 */
class ProductCategorySearchProcessor implements ProductCategorySearchProcessorInterface
{

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param string $locale
     * @return array
     */
    public function process(array &$resultSet, array $processedResultSet, $locale)
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
