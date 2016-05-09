<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Aggregation\Nested;
use Elastica\Aggregation\Stats;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;
use Spryker\Client\Search\Plugin\Config\SearchConfig;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\FacetQueryExpanderPlugin;

abstract class AbstractFacetQueryExpanderPluginTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider facetQueryExpanderDataProvider
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface $searchConfig
     * @param array $expectedAggregations
     * @param array $params
     *
     * @return void
     */
    public function testFacetQueryExpanderShouldCreateAggregationsBasedOnSearchConfig(SearchConfigInterface $searchConfig, array $expectedAggregations, array $params = [])
    {
        $queryExpander = new FacetQueryExpanderPlugin();
        $query = $queryExpander->expandQuery($this->createQueryMock(), $searchConfig, $params);

        $aggregations = $query->getSearchQuery($params)->getParam('aggs');

        $this->assertEquals($expectedAggregations, $aggregations);
    }

    /**
     * @return array
     */
    abstract public function facetQueryExpanderDataProvider();

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
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function getExpectedStringFacetAggregation()
    {
        return (new Nested(PageIndexMap::STRING_FACET, PageIndexMap::STRING_FACET))
            ->addAggregation((new Terms(PageIndexMap::STRING_FACET . '-name'))
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
                    ->setParameterName('foo')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(FacetConfigBuilder::TYPE_ENUMERATION)
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
                    ->setParameterName('foo')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(FacetConfigBuilder::TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(FacetConfigBuilder::TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(FacetConfigBuilder::TYPE_BOOL)
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
                    ->setParameterName('foo')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(FacetConfigBuilder::TYPE_ENUMERATION)
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
                    ->setParameterName('foo')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(FacetConfigBuilder::TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(FacetConfigBuilder::TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(FacetConfigBuilder::TYPE_RANGE)
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
                    ->setParameterName('foo')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(FacetConfigBuilder::TYPE_CATEGORY)
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
                    ->setParameterName('foo')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(FacetConfigBuilder::TYPE_CATEGORY)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(FacetConfigBuilder::TYPE_CATEGORY)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(FacetConfigBuilder::TYPE_CATEGORY)
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
                    ->setParameterName('foo')
                    ->setFieldName(PageIndexMap::STRING_FACET)
                    ->setType(FacetConfigBuilder::TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                    ->setType(FacetConfigBuilder::TYPE_ENUMERATION)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(FacetConfigBuilder::TYPE_CATEGORY)
            );

        return $searchConfig;
    }

}
