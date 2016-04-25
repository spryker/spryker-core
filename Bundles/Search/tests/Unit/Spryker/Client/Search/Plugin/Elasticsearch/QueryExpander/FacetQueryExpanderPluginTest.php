<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\Model\Query\QueryInterface;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;
use Spryker\Client\Search\Plugin\Config\SearchConfig;
use Spryker\Client\Search\Plugin\Config\SearchConfigInterface;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\FacetQueryExpanderPlugin;

/**
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group FacetQueryExpanderPluginTest
 */
class FacetQueryExpanderPluginTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider facetQueryExpanderDataProvider
     *
     * @param \Spryker\Client\Search\Plugin\Config\SearchConfigInterface $searchConfig
     * @param array $expectedAggregations
     * @param array $params
     *
     * @return void
     */
    public function testFacetQueryExpanderShouldCreateAggregationsBasedOnSearchConfig(SearchConfigInterface $searchConfig, array $expectedAggregations, array $params = [])
    {
        $queryExpander = new FacetQueryExpanderPlugin();
        $query = $queryExpander->expandQuery($this->createQueryMock(), $searchConfig, $params);

        $result = $query->getSearchQuery($params)->toArray();

        $this->assertEquals($expectedAggregations, $result['aggs']);
    }

    /**
     * @return array
     */
    public function facetQueryExpanderDataProvider()
    {
        return [
            // facet aggregation without filter
            'single string facet' => $this->createStringFacetData(),
            'multiple string facets' => $this->createMultiStringFacetData(),
            'single integer facet' => $this->createIntegerFacetData(),
            'multiple integer facets' => $this->createMultiIntegerFacetData(),
            'single category facet' => $this->createCategoryFacetData(),
            'multiple category facets' => $this->createMultiCategoryFacetData(),
            'mixed facets' => $this->createMixedFacetData(),

            // facet aggregations with filter
//            'filtered single string facet' => $this->createFilteredStringFacetData(),
//            'filtered multiple string facets' => $this->createMultiFilteredStringFacetData(),
        ];
    }

    /**
     * @return array
     */
    protected function createStringFacetData()
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

        $expectedAggregations = [
            PageIndexMap::STRING_FACET => $this->getExpectedStringFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createMultiStringFacetData()
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

        $expectedAggregations = [
            PageIndexMap::STRING_FACET => $this->getExpectedStringFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createIntegerFacetData()
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

        $expectedAggregations = [
            PageIndexMap::INTEGER_FACET => $this->getExpectedIntegerFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createMultiIntegerFacetData()
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

        $expectedAggregations = [
            PageIndexMap::INTEGER_FACET => $this->getExpectedIntegerFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createCategoryFacetData()
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

        $expectedAggregations = [
            PageIndexMap::CATEGORY_ALL_PARENTS => $this->getExpectedCategoryFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createMultiCategoryFacetData()
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

        $expectedAggregations = [
            PageIndexMap::CATEGORY_ALL_PARENTS => $this->getExpectedCategoryFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createMixedFacetData()
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
                    ->setType(FacetConfigBuilder::TYPE_RANGE)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz')
                    ->setFieldName(PageIndexMap::CATEGORY_ALL_PARENTS)
                    ->setType(FacetConfigBuilder::TYPE_CATEGORY)
            );

        $expectedAggregations = [
            PageIndexMap::STRING_FACET => $this->getExpectedStringFacetAggregation(),
            PageIndexMap::INTEGER_FACET => $this->getExpectedIntegerFacetAggregation(),
            PageIndexMap::CATEGORY_ALL_PARENTS => $this->getExpectedCategoryFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createFilteredStringFacetData()
    {
        $data = $this->createStringFacetData();

        $data[] = [
            'foo' => 'asdf',
        ];

        return $data;
    }

    /**
     * @return array
     */
    protected function createMultiFilteredStringFacetData()
    {
        $data = $this->createMultiStringFacetData();

        // FIXME: expected aggregation should be different?

        $data[] = [
            'foo' => 'asdf',
        ];

        return $data;
    }

    /**
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
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
     * @return \Spryker\Client\Search\Plugin\Config\SearchConfigInterface
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
    protected function getExpectedStringFacetAggregation()
    {
        return [
            'nested' => [
                'path' => 'string-facet',
            ],
            'aggs' => [
                'string-facet-name' => [
                    'terms' => [
                        'field' => 'string-facet.facet-name',
                    ],
                    'aggs' => [
                        'string-facet-value' => [
                            'terms' => [
                                'field' => 'string-facet.facet-value',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getExpectedIntegerFacetAggregation()
    {
        return [
            'nested' => [
                'path' => 'integer-facet',
            ],
            'aggs' => [
                'integer-facet-name' => [
                    'terms' => [
                        'field' => 'integer-facet.facet-name',
                    ],
                    'aggs' => [
                        'integer-facet-stats' => [
                            'stats' => [
                                'field' => 'integer-facet.facet-value',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getExpectedCategoryFacetAggregation()
    {
        return [
            'terms' => [
                'field' => 'category.all-parents',
            ],
        ];
//        return [
//            'global' => new \stdClass(),
//            'aggs' => [
//                'category' => [
//                    'filter' => [
//                        'bool' => new \stdClass(),
//                    ],
//                    'aggs' => [
//                        'category' => [
//                            'terms' => [
//                                'field' => 'category.all-parents',
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//        ];
    }

}
