<?php

namespace SprykerFeature\Zed\ProductCategorySearch\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * Class ProductCategorySearchDependencyContainer
 * @package SprykerFeature\Zed\ProductCategory\Business
 */
/**
 * @method ProductCategorySearchDependencyContainer getDependencyContainer()
 */
class ProductCategorySearchFacade extends AbstractFacade
{

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param string $locale
     * @return array
     */
    public function processProductCategorySearchData(array &$resultSet, array $processedResultSet, $locale)
    {
        return $this->getDependencyContainer()
            ->createProductCategorySearchProcessor()
            ->process($resultSet, $processedResultSet, $locale);
    }
}
