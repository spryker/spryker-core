<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\SearchPage\Communication\SearchPageDependencyContainer;

/**
 * @method SearchPageDependencyContainer getDependencyContainer()
 */
class SearchPageConfigProcessorPlugin extends AbstractPlugin
{

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'search_page_config';
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->getSearchPageFacade()
            ->processSearchPageConfig($resultSet, $locale)
        ;
    }

}
