<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Codeception\Test\Unit;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\Stats;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;
use Spryker\Client\Search\Plugin\Config\PaginationConfigBuilder;
use Spryker\Client\Search\Plugin\Config\SearchConfig;
use Spryker\Client\Search\Plugin\Config\SortConfigBuilder;
use Spryker\Client\Search\SearchConfig as ClientSearchConfig;
use Spryker\Client\Search\SearchDependencyProvider;
use Spryker\Client\Search\SearchFactory;
use Spryker\Shared\Search\SearchConfig as SharedSearchConfig;
use SprykerTest\Client\Search\Plugin\Elasticsearch\Fixtures\BaseQueryPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group AbstractQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
abstract class AbstractQueryExpanderPluginTest extends Unit
{
    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function createBaseQueryPlugin()
    {
        return new BaseQueryPlugin();
    }

    /**
     * @return \Elastica\Query
     */
    protected function createBaseQuery()
    {
        $baseQuery = (new Query())
            ->setQuery(new BoolQuery());

        return $baseQuery;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createSearchConfigMock()
    {
        $searchConfigMock = $this->getMockBuilder(SearchConfig::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFacetConfigBuilder', 'getSortConfigBuilder', 'getPaginationConfigBuilder'])
            ->getMock();

        $searchConfigMock
            ->method('getFacetConfigBuilder')
            ->willReturn(new FacetConfigBuilder());

        $searchConfigMock
            ->method('getSortConfigBuilder')
            ->willReturn(new SortConfigBuilder());

        $searchConfigMock
            ->method('getPaginationConfigBuilder')
            ->willReturn(new PaginationConfigBuilder());

        return $searchConfigMock;
    }

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface $searchConfig
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Search\SearchFactory
     */
    protected function createSearchFactoryMockedWithSearchConfig(SearchConfigInterface $searchConfig)
    {
        /** @var \Spryker\Client\Search\SearchFactory|\PHPUnit\Framework\MockObject\MockObject $searchFactoryMock */
        $searchFactoryMock = $this->getMockBuilder(SearchFactory::class)
            ->setMethods(['getSearchConfig', 'getConfig'])
            ->getMock();

        $searchFactoryMock
            ->method('getSearchConfig')
            ->willReturn($searchConfig);

        $searchFactoryMock
            ->method('getConfig')
            ->willReturn(new ClientSearchConfig());

        $container = new Container();
        $searchDependencyProvider = new SearchDependencyProvider();
        $searchDependencyProvider->provideServiceLayerDependencies($container);
        $searchFactoryMock->setContainer($container);

        return $searchFactoryMock;
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function getExpectedStringFacetAggregation()
    {
        return (new Nested(PageIndexMap::STRING_FACET, PageIndexMap::STRING_FACET))
            ->addAggregation((new Terms(PageIndexMap::STRING_FACET . '-name'))
                ->setSize(ClientSearchConfig::FACET_NAME_AGGREGATION_SIZE)
                ->setField(PageIndexMap::STRING_FACET_FACET_NAME)
                ->addAggregation((new Terms(PageIndexMap::STRING_FACET . '-value'))
                    ->setField(PageIndexMap::STRING_FACET_FACET_VALUE)));
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function getExpectedIntegerFacetAggregation()
    {
        return (new Nested(PageIndexMap::INTEGER_FACET, PageIndexMap::INTEGER_FACET))
            ->addAggregation((new Terms(PageIndexMap::INTEGER_FACET . '-name'))
                ->setSize(ClientSearchConfig::FACET_NAME_AGGREGATION_SIZE)
                ->setField(PageIndexMap::INTEGER_FACET_FACET_NAME)
                ->addAggregation((new Stats(PageIndexMap::INTEGER_FACET . '-stats'))
                    ->setField(PageIndexMap::INTEGER_FACET_FACET_VALUE)));
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function getExpectedCategoryFacetAggregation()
    {
        return (new Terms(PageIndexMap::CATEGORY_ALL_PARENTS))
            ->setField(PageIndexMap::CATEGORY_ALL_PARENTS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createStringSearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            );

        return $searchConfig;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createMultiStringSearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            );

        return $searchConfig;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createIntegerSearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            );

        return $searchConfig;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createMultiIntegerSearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_RANGE)
            );

        return $searchConfig;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createCategorySearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SharedSearchConfig::FACET_TYPE_CATEGORY)
            );

        return $searchConfig;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createMultiCategorySearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SharedSearchConfig::FACET_TYPE_CATEGORY)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SharedSearchConfig::FACET_TYPE_CATEGORY)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SharedSearchConfig::FACET_TYPE_CATEGORY)
            );

        return $searchConfig;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected function createMixedSearchConfig()
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SharedSearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SharedSearchConfig::FACET_TYPE_CATEGORY)
            );

        return $searchConfig;
    }

    /**
     * @return \Spryker\Client\Search\SearchFactory
     */
    protected function getSearchFactory()
    {
        return new SearchFactory();
    }
}
