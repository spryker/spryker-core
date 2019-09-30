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
use Spryker\Client\SearchElasticsearch\Config\SortConfig;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig as ClientSearchElasticsearchConfig;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchDependencyProvider;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory;
use Spryker\Client\SearchExtension\Config\FacetConfigInterface;
use Spryker\Client\SearchExtension\Config\PaginationConfigInterface;
use Spryker\Client\SearchExtension\Config\SortConfigInterface;
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
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createFacetConfig(): FacetConfigInterface
    {
        return new FacetConfig();
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\SortConfigInterface
     */
    protected function createSortConfig(): SortConfigInterface
    {
        return new SortConfig();
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\PaginationConfigInterface
     */
    protected function createPaginationConfig(): PaginationConfigInterface
    {
        return new PaginationConfig();
    }

    /**
     * @param \Spryker\Client\SearchExtension\Config\FacetConfigInterface $facetConfig
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory
     */
    protected function createSearchElasticsearchFactoryMockWithFacetConfig(FacetConfigInterface $facetConfig)
    {
        $searchElasticsearchFactoryMock = $this->createSearchElasticsearchFactoryMock(['getFacetConfig']);
        $searchElasticsearchFactoryMock->method('getFacetConfig')->willReturn($facetConfig);

        return $searchElasticsearchFactoryMock;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Config\SortConfigInterface $sortConfig
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory
     */
    protected function createSearchElasticsearchFactoryMockWithSortConfig(SortConfigInterface $sortConfig)
    {
        $searchElasticsearchFactoryMock = $this->createSearchElasticsearchFactoryMock(['getSortConfig']);
        $searchElasticsearchFactoryMock->method('getSortConfig')->willReturn($sortConfig);

        return $searchElasticsearchFactoryMock;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Config\PaginationConfigInterface $paginationConfig
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory
     */
    protected function createSearchElasticsearchFactoryMockWithPaginationConfig(PaginationConfigInterface $paginationConfig)
    {
        $searchElasticsearchFactoryMock = $this->createSearchElasticsearchFactoryMock(['getPaginationConfig']);
        $searchElasticsearchFactoryMock->method('getPaginationConfig')->willReturn($paginationConfig);

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
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createStringSearchConfig(): FacetConfigInterface
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
            (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
        );

        return $facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createMultiStringSearchConfig(): FacetConfigInterface
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
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

        return $facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createIntegerSearchConfig(): FacetConfigInterface
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
            (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_ENUMERATION)
        );

        return $facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createMultiIntegerSearchConfig(): FacetConfigInterface
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
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

        return $facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createCategorySearchConfig(): FacetConfigInterface
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
            (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(SearchElasticsearchConfig::FACET_TYPE_CATEGORY)
        );

        return $facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createMultiCategorySearchConfig(): FacetConfigInterface
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
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

        return $facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Config\FacetConfigInterface
     */
    protected function createMixedSearchConfig(): FacetConfigInterface
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
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

        return $facetConfig;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory
     */
    protected function getSearchElasticsearchFactory(): SearchElasticsearchFactory
    {
        return new SearchElasticsearchFactory();
    }
}
