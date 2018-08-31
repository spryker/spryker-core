<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch;

use Spryker\Client\CmsPageSearchPage\CmsPageSearchDependencyProvider;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface;

class CmsPageSearchFactory extends AbstractFactory
{
    /**
     * @param string $searchString
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function createCmsPageSearchQuery(string $searchString): QueryInterface
    {
        $searchQuery = $this->getCmsPageSearchQueryPlugin();

        if ($searchQuery instanceof SearchStringSetterInterface) {
            $searchQuery->setSearchString($searchString);
        }

        return $searchQuery;
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient(): SearchClientInterface
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function getCmsPageSearchQueryPlugin(): QueryInterface
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::CMS_PAGE_SEARCH_QUERY_PLUGIN);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getCmsPageSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::CMS_PAGE_SEARCH_QUERY_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getCmsPageSearchResultFormatters(): array
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::CMS_PAGE_SEARCH_RESULT_FORMATTER_PLUGINS);
    }
}
