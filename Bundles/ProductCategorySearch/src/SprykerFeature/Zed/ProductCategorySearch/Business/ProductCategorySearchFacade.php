<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategorySearch\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductCategorySearchDependencyContainer getDependencyContainer()
 */
class ProductCategorySearchFacade extends AbstractFacade
{

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function processProductCategorySearchData(array &$resultSet, array $processedResultSet, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createProductCategorySearchProcessor()
            ->process($resultSet, $processedResultSet, $locale);
    }

}
