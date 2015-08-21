<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\SearchPage\Communication\SearchPageDependencyContainer;

/**
 * @method SearchPageDependencyContainer getDependencyContainer()
 */
class SearchPageConfigQueryExpanderPlugin extends AbstractPlugin
{

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'search_page_config';
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        $queryContainer = $this->getDependencyContainer()->getSearchPageQueryContainer();

        return $queryContainer->expandSearchPageConfigQuery($expandableQuery);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }

}
