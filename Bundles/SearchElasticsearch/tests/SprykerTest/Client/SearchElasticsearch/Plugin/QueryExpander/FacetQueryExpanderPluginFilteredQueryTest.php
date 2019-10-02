<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query\BoolQuery;
use Elastica\Query\Nested;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
 * @group QueryExpander
 * @group FacetQueryExpanderPluginFilteredQueryTest
 * Add your own group annotations below this line
 */
class FacetQueryExpanderPluginFilteredQueryTest extends AbstractFacetQueryExpanderPluginQueryTest
{
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
        $facetConfig = $this->createStringSearchConfig();

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

        return [$facetConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createMultiFilteredStringFacetData(): array
    {
        $facetConfig = $this->createMultiStringSearchConfig();
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

        return [$facetConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredStringFacetDataWithMultipleValues(): array
    {
        $facetConfig = $this->createStringSearchConfig();

        $expectedQuery = (new BoolQuery())
            ->addFilter((new Nested())
                ->setPath(PageIndexMap::STRING_FACET)
                ->setQuery((new BoolQuery())
                    ->addFilter((new Term())
                        ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'foo'))
                    ->addFilter((new Terms())
                        ->setTerms(PageIndexMap::STRING_FACET_FACET_VALUE, ['asdf', 'qwer']))));
        $parameters = [
            'foo-param' => [
                'asdf',
                'qwer',
            ],
        ];

        return [$facetConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createMultiValuedFilteredStringFacetData(): array
    {
        $facetConfig = $this->createStringSearchConfig();
        $facetConfig->get('foo')->setIsMultiValued(true);

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

        return [$facetConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredIntegerFacetData(): array
    {
        $facetConfig = $this->createIntegerSearchConfig();
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

        return [$facetConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredPriceRangeFacetData(): array
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
            (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                ->setType(SearchElasticsearchConfig::FACET_TYPE_PRICE_RANGE)
        )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                ->setType(SearchElasticsearchConfig::FACET_TYPE_PRICE_RANGE)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                ->setType(SearchElasticsearchConfig::FACET_TYPE_PRICE_RANGE)
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

                return [$facetConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredOpenPriceRangeFacetData(): array
    {
        $facetConfig = $this->createFacetConfig();
        $facetConfig->addFacet(
            (new FacetConfigTransfer())
                    ->setName('foo')
                    ->setParameterName('foo-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                ->setType(SearchElasticsearchConfig::FACET_TYPE_PRICE_RANGE)
        )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('bar')
                    ->setParameterName('bar-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                ->setType(SearchElasticsearchConfig::FACET_TYPE_PRICE_RANGE)
            )
            ->addFacet(
                (new FacetConfigTransfer())
                    ->setName('baz')
                    ->setParameterName('baz-param')
                    ->setFieldName(PageIndexMap::INTEGER_FACET)
                ->setType(SearchElasticsearchConfig::FACET_TYPE_PRICE_RANGE)
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

                return [$facetConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createMultiFilteredIntegerFacetData(): array
    {
        $facetConfig = $this->createMultiIntegerSearchConfig();
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

                return [$facetConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createMultiValuedFilteredIntegerFacetData(): array
    {
        $facetConfig = $this->createIntegerSearchConfig();
        $facetConfig->get('foo')
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

        return [$facetConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredCategoryFacetData(): array
    {
        $facetConfig = $this->createCategorySearchConfig();
        $expectedQuery = (new BoolQuery())
            ->addFilter((new Term())
                ->setTerm(PageIndexMap::CATEGORY_ALL_PARENTS, 'c1'));

        $parameters = [
            'foo-param' => 'c1',
        ];

        return [$facetConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredMixedFacetData(): array
    {
        $facetConfig = $this->createMixedSearchConfig();
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

        return [$facetConfig, $expectedQuery, $parameters];
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
        $facetConfig = $this->createMixedSearchConfig();
        $expectedQuery = (new BoolQuery());

        $parameters = [
            'foo-param' => false,
            'bar-param' => null,
            'baz-param' => '',
        ];

        return [$facetConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredStringFacetDataWithMultipleIncorrectValues(): array
    {
        $facetConfig = $this->createMixedSearchConfig();
        $expectedQuery = new BoolQuery();

        $parameters = [
            'foo-param' => [
                false,
                null,
                '',
            ],
        ];

        return [$facetConfig, $expectedQuery, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredZeroValuesFacetData(): array
    {
        $facetConfig = $this->createMixedSearchConfig();
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

        return [$facetConfig, $expectedQuery, $parameters];
    }
}
