<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Index;
use Elastica\Query\BoolQuery;
use Elastica\Query\Nested;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Shared\Search\SearchConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group FacetQueryExpanderPluginFilteredQueryTest
 * Add your own group annotations below this line
 */
class FacetQueryExpanderPluginFilteredQueryTest extends AbstractFacetQueryExpanderPluginQueryTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->skipIfElasticsearch7();

        parent::setUp();
    }

    /**
     * @return array
     */
    public function facetQueryExpanderDataProvider(): array
    {
        return [
            'filtered single string facet' => $this->createFilteredStringFacetData(),
            'filtered multiple string facets' => $this->createMultiFilteredStringFacetData(),
            'filtered string facets with multiple values' => $this->createFilteredStringFacetDataWithMultipleValues(),
            'filtered multi-valued string facets' => $this->createMultiValuedFilteredStringFacetData(),
            'filtered single integer facet' => $this->createFilteredIntegerFacetData(),
            'filtered single price range facet' => $this->createFilteredPriceRangeFacetData(),
            'filtered open price range facet' => $this->createFilteredOpenPriceRangeFacetData(),
            'filtered multiple integer facets' => $this->createMultiFilteredIntegerFacetData(),
            'filtered multi-valued integer facets' => $this->createMultiValuedFilteredIntegerFacetData(),
            'filtered single category facet' => $this->createFilteredCategoryFacetData(),
            'filtered mixed facets' => $this->createFilteredMixedFacetData(),
            'filtered single incorrect value facet' => $this->createFilteredIncorrectStringFacetData(),
            'filtered multiple incorrect value facets' => $this->createMultiFilteredIncorrectValuesFacetData(),
            'filtered string facet with multiple incorrect values' => $this->createFilteredStringFacetDataWithMultipleIncorrectValues(),
            'filtered zero value facets' => $this->createFilteredZeroValuesFacetData(),
        ];
    }

    /**
     * @return array
     */
    protected function createFilteredStringFacetData(): array
    {
        $searchConfig = $this->createStringSearchConfig();

        $expectedQuery = (new BoolQuery())
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::STRING_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'foo'))
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, 'asdf'))));

        $parameters = [
            'foo-param' => 'asdf',
        ];

        return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createMultiFilteredStringFacetData(): array
    {
        $searchConfig = $this->createMultiStringSearchConfig();
        $expectedQuery = (new BoolQuery())
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::STRING_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'foo'))
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, 'asdf'))))
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::STRING_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'bar'))
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, 'qwer'))))
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::STRING_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'baz'))
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, 'yxcv'))));

        $parameters = [
            'foo-param' => 'asdf',
            'bar-param' => 'qwer',
            'baz-param' => 'yxcv',
        ];

        return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredStringFacetDataWithMultipleValues(): array
    {
        $searchConfig = $this->createStringSearchConfig();

        $expectedQuery = (new BoolQuery())
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::STRING_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'foo'))
                    ->addFilter((new Terms(PageIndexMap::STRING_FACET_FACET_VALUE))
                        ->setTerms(['asdf', 'qwer']))));
        $parameters = [
            'foo-param' => [
                'asdf',
                'qwer',
            ],
        ];

        return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createMultiValuedFilteredStringFacetData(): array
    {
        /*
         * Filter criteria should be: (foo == 'asdf' || foo == 'qwer' || foo == 'yxcv')
         * "bar" is not multi-valued so all the value will be converted to separated term queries, while "baz" is multi-
         * valued so it will generate a bool query with "should" criteria per each value.
         */
        $searchConfig = $this->createStringSearchConfig();

        // set "foo" to multi-valued
        $searchConfig
            ->getFacetConfigBuilder()
            ->get('foo')
            ->setIsMultiValued(true);

        $expectedQuery = (new BoolQuery())
            ->addFilter((new BoolQuery())
                ->addShould((new Nested())
                    ->setPath(PageIndexMap::STRING_FACET)
                    ->setQuery((new BoolQuery())
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'foo'))
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, 'asdf'))))
                ->addShould((new Nested())
                    ->setPath(PageIndexMap::STRING_FACET)
                    ->setQuery((new BoolQuery())
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'foo'))
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, 'qwer'))))
                ->addShould((new Nested())
                    ->setPath(PageIndexMap::STRING_FACET)
                    ->setQuery((new BoolQuery())
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'foo'))
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, 'yxcv')))));

        $parameters = [
            'foo-param' => [
                'asdf',
                'qwer',
                'yxcv',
            ],
        ];

        return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredIntegerFacetData(): array
    {
        $searchConfig = $this->createIntegerSearchConfig();
        $expectedQuery = (new BoolQuery())
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::INTEGER_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'foo'))
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_VALUE, 123))));

        $parameters = [
            'foo-param' => 123,
        ];

        return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredPriceRangeFacetData(): array
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                ->setType(SearchConfig::FACET_TYPE_PRICE_RANGE)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                ->setType(SearchConfig::FACET_TYPE_PRICE_RANGE)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                ->setType(SearchConfig::FACET_TYPE_PRICE_RANGE)
            );

        $expectedQuery = (new BoolQuery())
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::INTEGER_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'foo'))
                    ->addFilter((new Range())
                        ->addField(PageIndexMap::INTEGER_FACET_FACET_VALUE, [
                            'gte' => 12300,
                        ]))))
                ->addFilter((new Nested())
                ->setPath(PageIndexMap::INTEGER_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'bar'))
                    ->addFilter((new Range())
                        ->addField(PageIndexMap::INTEGER_FACET_FACET_VALUE, [
                            'lte' => 78900,
                            'gte' => 45600,
                        ]))))
                ->addFilter((new Nested())
                ->setPath(PageIndexMap::INTEGER_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'baz'))
                    ->addFilter((new Range())
                        ->addField(PageIndexMap::INTEGER_FACET_FACET_VALUE, [
                            'lte' => 45600,
                            'gte' => 12300,
                        ]))));

                $parameters = [
                    'foo-param' => 123, // simple value
                    'bar-param' => '456-789', // range value as string
                    'baz-param' => [
                        'min' => 123,
                        'max' => 456,
                    ], // range value as array
                ];

                return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredOpenPriceRangeFacetData(): array
    {
        $searchConfig = $this->createSearchConfigMock();
        $searchConfig->getFacetConfigBuilder()
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                ->setType(SearchConfig::FACET_TYPE_PRICE_RANGE)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                ->setType(SearchConfig::FACET_TYPE_PRICE_RANGE)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                ->setType(SearchConfig::FACET_TYPE_PRICE_RANGE)
            );

        $expectedQuery = (new BoolQuery())
                ->addFilter((new Nested())
                ->setPath(PageIndexMap::INTEGER_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'foo'))
                    ->addFilter((new Range())
                        ->addField(PageIndexMap::INTEGER_FACET_FACET_VALUE, [
                            'gte' => 12300,
                        ]))))
                ->addFilter((new Nested())
                ->setPath(PageIndexMap::INTEGER_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'bar'))
                    ->addFilter((new Range())
                        ->addField(PageIndexMap::INTEGER_FACET_FACET_VALUE, [
                            'lte' => 12300,
                        ]))))
                ->addFilter((new Nested())
                ->setPath(PageIndexMap::INTEGER_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'baz'))
                    ->addFilter((new Range())
                        ->addField(PageIndexMap::INTEGER_FACET_FACET_VALUE, []))));

                $parameters = [
                    'foo-param' => [
                        'min' => 123,
                    ], // open range
                    'bar-param' => [
                        'max' => 123,
                    ], // open range
                    'baz-param' => '-', // empty range
                ];

                return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createMultiFilteredIntegerFacetData(): array
    {
        $searchConfig = $this->createMultiIntegerSearchConfig();
        $expectedQuery = (new BoolQuery())
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::INTEGER_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'foo'))
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_VALUE, 123))))
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::INTEGER_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'bar'))
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_VALUE, 456))))
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::INTEGER_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'baz'))
                    // "baz" is range type so we expect a range filter
                    ->addFilter((new Range())
                        ->addField(PageIndexMap::INTEGER_FACET_FACET_VALUE, [
                            'gte' => 789,
                        ]))));

                $parameters = [
                    'foo-param' => 123,
                    'bar-param' => 456,
                    'baz-param' => 789,
                ];

                return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createMultiValuedFilteredIntegerFacetData(): array
    {
        $searchConfig = $this->createIntegerSearchConfig();

        // set "foo" to multi-valued
        $searchConfig
            ->getFacetConfigBuilder()
            ->get('foo')
            ->setIsMultiValued(true);

        $expectedQuery = (new BoolQuery())
            ->addFilter((new BoolQuery())
                ->addShould((new Nested())
                    ->setPath(PageIndexMap::INTEGER_FACET)
                    ->setQuery((new BoolQuery())
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'foo'))
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::INTEGER_FACET_FACET_VALUE, 123))))
                ->addShould((new Nested())
                    ->setPath(PageIndexMap::INTEGER_FACET)
                    ->setQuery((new BoolQuery())
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'foo'))
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::INTEGER_FACET_FACET_VALUE, 456))))
                ->addShould((new Nested())
                    ->setPath(PageIndexMap::INTEGER_FACET)
                    ->setQuery((new BoolQuery())
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'foo'))
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::INTEGER_FACET_FACET_VALUE, 789)))));

        $parameters = [
            'foo-param' => [
                123,
                456,
                789,
            ],
        ];

        return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredCategoryFacetData(): array
    {
        $searchConfig = $this->createCategorySearchConfig();
        $expectedQuery = (new BoolQuery())
            ->addFilter((new Term())
                ->setTerm(PageIndexMap::CATEGORY_ALL_PARENTS, 'c1'));

        $parameters = [
            'foo-param' => 'c1',
        ];

        return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredMixedFacetData(): array
    {
        $searchConfig = $this->createMixedSearchConfig();
        $expectedQuery = (new BoolQuery())
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::STRING_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'foo'))
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, 'asdf'))))
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::INTEGER_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'bar'))
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_VALUE, 456))))
            ->addFilter((new Term())
                ->setTerm(PageIndexMap::CATEGORY_ALL_PARENTS, 'c1'));

        $parameters = [
            'foo-param' => 'asdf',
            'bar-param' => 456,
            'baz-param' => 'c1',
        ];

        return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredIncorrectStringFacetData(): array
    {
        $searchConfig = $this->createMixedSearchConfig();
        $expectedQuery = new BoolQuery();

        $parameters = [
            'baz-param' => '',
        ];

        return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createMultiFilteredIncorrectValuesFacetData(): array
    {
        $searchConfig = $this->createMixedSearchConfig();
        $expectedQuery = (new BoolQuery());

        $parameters = [
            'foo-param' => false,
            'bar-param' => null,
            'baz-param' => '',
        ];

        return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredStringFacetDataWithMultipleIncorrectValues(): array
    {
        $searchConfig = $this->createMixedSearchConfig();
        $expectedQuery = new BoolQuery();

        $parameters = [
            'foo-param' => [
                false,
                null,
                '',
            ],
        ];

        return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredZeroValuesFacetData(): array
    {
        $searchConfig = $this->createMixedSearchConfig();
        $expectedQuery = (new BoolQuery())
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::STRING_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'foo'))
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, '0'))))
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::INTEGER_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'bar'))
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::INTEGER_FACET_FACET_VALUE, 0))));

        $parameters = [
            'foo-param' => '0',
            'bar-param' => 0,
        ];

        return [$searchConfig, $expectedQuery, $parameters];
    }

    /**
     * @return void
     */
    protected function skipIfElasticsearch7(): void
    {
        if (!method_exists(Index::class, 'getType')) {
            $this->markTestSkipped('This test is not suitable for Elasticsearch 7 or higher');
        }
    }
}
