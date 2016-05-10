<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;
use Spryker\Client\Search\Plugin\Config\SearchConfig;

/**
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group FacetQueryExpanderPlugin
 */
class FacetQueryExpanderPluginQueryTest extends AbstractFacetQueryExpanderPluginQueryTest
{

    /**
     * @return array
     */
    public function facetQueryExpanderDataProvider()
    {
        return [
            'single string facet' => $this->createStringFacetData(),
            'multiple string facets' => $this->createMultiStringFacetData(),
            'single integer facet' => $this->createIntegerFacetData(),
            'multiple integer facets' => $this->createMultiIntegerFacetData(),
            'single category facet' => $this->createCategoryFacetData(),
            'multiple category facets' => $this->createMultiCategoryFacetData(),
            'mixed facets' => $this->createMixedFacetData(),
        ];
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    protected function createQueryMock()
    {
        $baseQuery = new Query();
        $baseQuery->setQuery(new BoolQuery());

        $queryMock = $this->getMockBuilder(QueryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryMock
            ->method('getSearchQuery')
            ->willReturn($baseQuery);

        return $queryMock;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createSearchConfigMock()
    {
        $searchConfigMock = $this->getMockBuilder(SearchConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $searchConfigMock
            ->method('getFacetConfigBuilder')
            ->willReturn(new FacetConfigBuilder());

        return $searchConfigMock;
    }

    /**
     * @return array
     */
    protected function createStringFacetData()
    {
        $searchConfig = $this->createStringSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createMultiStringFacetData()
    {
        $searchConfig = $this->createMultiStringSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createIntegerFacetData()
    {
        $searchConfig = $this->createIntegerSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createMultiIntegerFacetData()
    {
        $searchConfig = $this->createMultiIntegerSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createCategoryFacetData()
    {
        $searchConfig = $this->createCategorySearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createMultiCategoryFacetData()
    {
        $searchConfig = $this->createMultiCategorySearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createMixedFacetData()
    {
        $searchConfig = $this->createMixedSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

}
