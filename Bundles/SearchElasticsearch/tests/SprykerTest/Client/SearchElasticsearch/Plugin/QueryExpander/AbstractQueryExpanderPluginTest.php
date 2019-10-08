<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\QueryExpander;

use Codeception\Test\Unit;
use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\Stats;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SearchElasticsearch\Config\FacetConfig;
use Spryker\Client\SearchElasticsearch\Config\PaginationConfig;
use Spryker\Client\SearchElasticsearch\Config\SearchConfig;
use Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface;
use Spryker\Client\SearchElasticsearch\Config\SortConfig;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig as ClientSearchElasticsearchConfig;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchDependencyProvider;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig;
use SprykerTest\Client\SearchElasticsearch\Plugin\Fixtures\BaseQueryPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
 * @group QueryExpander
 * @group AbstractQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
abstract class AbstractQueryExpanderPluginTest extends Unit
{
    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function createBaseQueryPlugin(): QueryInterface
    {
        return new BaseQueryPlugin();
    }

    /**
     * @return \Elastica\Query
     */
    protected function createBaseQuery(): Query
    {
        $baseQuery = (new Query())
            ->setQuery(new BoolQuery());

        return $baseQuery;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSearchConfigMock()
    {
        $searchConfigMock = $this->getMockBuilder(SearchConfig::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFacetConfig', 'getSortConfig', 'getPaginationConfig'])
            ->getMock();

        $searchConfigMock
            ->method('getFacetConfig')
            ->willReturn(new FacetConfig());

        $searchConfigMock
            ->method('getSortConfig')
            ->willReturn(new SortConfig());

        $searchConfigMock
            ->method('getPaginationConfig')
            ->willReturn(new PaginationConfig());

        return $searchConfigMock;
    }

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface $searchConfig
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Search\SearchFactory
     */
    protected function createSearchFactoryMockedWithSearchConfig(SearchConfigInterface $searchConfig)
    {
        /** @var \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory|\PHPUnit\Framework\MockObject\MockObject $searchElasticsearchFactoryMock */
        $searchElasticsearchFactoryMock = $this->getMockBuilder(SearchElasticsearchFactory::class)
            ->setMethods(['getSearchConfig', 'getConfig'])
            ->getMock();

        $searchElasticsearchFactoryMock
            ->method('getSearchConfig')
            ->willReturn($searchConfig);

        $searchElasticsearchFactoryMock
            ->method('getConfig')
            ->willReturn(new ClientSearchElasticsearchConfig());

        $container = new Container();
        $searchDependencyProvider = new SearchElasticsearchDependencyProvider();
        $searchDependencyProvider->provideServiceLayerDependencies($container);
        $searchElasticsearchFactoryMock->setContainer($container);

        return $searchElasticsearchFactoryMock;
    }

    /**
     * @param string[] $methodsToMock
     *
     * @return \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory|\PHPUnit\Framework\MockObject\MockObject $searchFactoryMock
     */
    protected function createSearchElasticsearchFactoryMock(array $methodsToMock = [])
    {
        array_push($methodsToMock, 'getConfig');

        /** @var \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory|\PHPUnit\Framework\MockObject\MockObject $searchFactoryMock */
        $searchFactoryMock = $this->getMockBuilder(SearchElasticsearchFactory::class)
            ->setMethods($methodsToMock)
            ->getMock();

        $searchFactoryMock
            ->method('getConfig')
            ->willReturn(new ClientSearchElasticsearchConfig());

        $container = new Container();
        $searchDependencyProvider = new SearchElasticsearchDependencyProvider();
        $searchDependencyProvider->provideServiceLayerDependencies($container);
        $searchFactoryMock->setContainer($container);

        return $searchFactoryMock;
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function getExpectedStringFacetAggregation(): AbstractAggregation
    {
        return (new Nested(PageIndexMap::STRING_FACET, PageIndexMap::STRING_FACET))
            ->addAggregation((new Terms(PageIndexMap::STRING_FACET . '-name'))
                ->setSize(ClientSearchElasticsearchConfig::FACET_NAME_AGGREGATION_SIZE)
                ->setField(PageIndexMap::STRING_FACET_FACET_NAME)
                ->addAggregation((new Terms(PageIndexMap::STRING_FACET . '-value'))
                    ->setField(PageIndexMap::STRING_FACET_FACET_VALUE)));
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function getExpectedIntegerFacetAggregation(): AbstractAggregation
    {
        return (new Nested(PageIndexMap::INTEGER_FACET, PageIndexMap::INTEGER_FACET))
            ->addAggregation((new Terms(PageIndexMap::INTEGER_FACET . '-name'))
                ->setSize(ClientSearchElasticsearchConfig::FACET_NAME_AGGREGATION_SIZE)
                ->setField(PageIndexMap::INTEGER_FACET_FACET_NAME)
                ->addAggregation((new Stats(PageIndexMap::INTEGER_FACET . '-stats'))
                    ->setField(PageIndexMap::INTEGER_FACET_FACET_VALUE)));
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function getExpectedCategoryFacetAggregation(): AbstractAggregation
    {
        return (new Terms(PageIndexMap::CATEGORY_ALL_PARENTS))
            ->setField(PageIndexMap::CATEGORY_ALL_PARENTS);
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createStringSearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getFacetConfig()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            );

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createMultiStringSearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getFacetConfig()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            );

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createIntegerSearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getFacetConfig()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            );

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createMultiIntegerSearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getFacetConfig()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_RANGE)
            );

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createCategorySearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getFacetConfig()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_CATEGORY)
            );

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createMultiCategorySearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getFacetConfig()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_CATEGORY)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_CATEGORY)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_CATEGORY)
            );

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface
     */
    protected function createMixedSearchConfig(): SearchConfigInterface
    {
        $searchConfigMock = $this->createSearchConfigMock();
        $searchConfigMock->getFacetConfig()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_CATEGORY)
            );

        return $searchConfigMock;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory
     */
    protected function getSearchElasticsearchFactory(): SearchElasticsearchFactory
    {
        return new SearchElasticsearchFactory();
    }
}
