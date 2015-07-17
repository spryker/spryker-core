<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategorySearch\Business\Processor;

use Generated\Shared\Transfer\LocaleTransfer;

class ProductCategorySearchProcessor implements ProductCategorySearchProcessorInterface
{

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function process(array &$resultSet, array $processedResultSet, LocaleTransfer $locale)
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
