<?php

namespace SprykerFeature\Zed\SearchPage\Communication\Plugin;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\SearchPage\Communication\SearchPageDependencyContainer;

/**
 * @method SearchPageDependencyContainer getDependencyContainer()
 */
class SearchPageConfigQueryExpanderPlugin implements QueryExpanderPluginInterface
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
     * @param LocaleDto $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleDto $locale)
    {
        $queryContainer = $this->getDependencyContainer()->getSearchPageQueryContainer();

        return $queryContainer->expandSearchPageConfigQuery($expandableQuery);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 1;
    }
}
