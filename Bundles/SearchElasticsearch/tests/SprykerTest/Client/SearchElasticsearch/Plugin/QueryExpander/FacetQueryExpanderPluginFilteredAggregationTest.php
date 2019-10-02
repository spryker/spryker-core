<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Aggregation\Filter;
use Elastica\Aggregation\GlobalAggregation;
use Elastica\Query\BoolQuery;
use Elastica\Query\Nested;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\FacetQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
 * @group QueryExpander
 * @group FacetQueryExpanderPluginFilteredAggregationTest
 * Add your own group annotations below this line
 */
class FacetQueryExpanderPluginFilteredAggregationTest extends AbstractFacetQueryExpanderPluginAggregationTest
{
    /**
     * @return array
     */
    public function facetQueryExpanderDataProvider(): array
    {
        return [
            'filtered single string facet' => $this->createFilteredStringFacetData(),
            'filtered multiple string facets' => $this->createMultiFilteredStringFacetData(),
            'filtered single integer facet' => $this->createFilteredIntegerFacetData(),
            'filtered multiple integer facets' => $this->createMultiFilteredIntegerFacetData(),
            'filtered single category facet' => $this->createFilteredCategoryFacetData(),
            'filtered mixed facets' => $this->createFilteredMixedFacetData(),
        ];
    }

    /**
     * @return array
     */
    protected function createFilteredStringFacetData(): array
    {
        $facetConfig = $this->createStringSearchConfig();
        $expectedStringFacetAggregation = $this->getExpectedStringFacetAggregation();

        $expectedAggregations = [
            $expectedStringFacetAggregation,
            // add global aggregation for "foo" filtered string facet
            (new GlobalAggregation(FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . 'foo'))
                ->addAggregation((new Filter(FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME))
                    ->setFilter((new BoolQuery()))
                    ->addAggregation($expectedStringFacetAggregation)),
        ];

        $parameters = [
            'foo-param' => 'asdf',
        ];

        return [$facetConfig, $expectedAggregations, $parameters];
    }

    /**
     * @return array
     */
    protected function createMultiFilteredStringFacetData(): array
    {
        $facetConfig = $this->createMultiStringSearchConfig();
        $expectedStringFacetAggregation = $this->getExpectedStringFacetAggregation();

        $expectedAggregations = [
            $expectedStringFacetAggregation,
            // add global aggregation for "foo" filtered string facet
            (new GlobalAggregation(FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . 'foo'))
                ->addAggregation((new Filter(FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME))
                    ->setFilter((new BoolQuery())
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
                                    ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, 'yxcv')))))
                    ->addAggregation($expectedStringFacetAggregation)),
            $expectedStringFacetAggregation,
            // add global aggregation for "bar" filtered string facet
            (new GlobalAggregation(FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . 'bar'))
                ->addAggregation((new Filter(FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME))
                    ->setFilter((new BoolQuery())
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
                                    ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'baz'))
                                ->addFilter((new Term())
                                    ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, 'yxcv')))))
                    ->addAggregation($expectedStringFacetAggregation)),
            $expectedStringFacetAggregation,
            // add global aggregation for "baz" filtered string facet
            (new GlobalAggregation(FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . 'baz'))
                ->addAggregation((new Filter(FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME))
                    ->setFilter((new BoolQuery())
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
                                    ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, 'qwer')))))
                    ->addAggregation($expectedStringFacetAggregation)),
        ];

        $parameters = [
            'foo-param' => 'asdf',
            'bar-param' => 'qwer',
            'baz-param' => 'yxcv',
        ];

        return [$facetConfig, $expectedAggregations, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredIntegerFacetData(): array
    {
        $facetConfig = $this->createIntegerSearchConfig();
        $expectedIntegerFacetAggregation = $this->getExpectedIntegerFacetAggregation();

        $expectedAggregations = [
            $expectedIntegerFacetAggregation,
            // add global aggregation for "foo" filtered integer facet
            (new GlobalAggregation(FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . 'foo'))
                ->addAggregation((new Filter(FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME))
                    ->setFilter((new BoolQuery()))
                    ->addAggregation($expectedIntegerFacetAggregation)),
        ];

        $parameters = [
            'foo-param' => 123,
        ];

        return [$facetConfig, $expectedAggregations, $parameters];
    }

    /**
     * @return array
     */
    protected function createMultiFilteredIntegerFacetData(): array
    {
        $facetConfig = $this->createMultiIntegerSearchConfig();
        $expectedIntegerFacetAggregation = $this->getExpectedIntegerFacetAggregation();

        $expectedAggregations = [
            $expectedIntegerFacetAggregation,
            // add global aggregation for "foo" filtered integer facet
            (new GlobalAggregation(FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . 'foo'))
                ->addAggregation((new Filter(FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME))
                    ->setFilter((new BoolQuery())
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
                                    ])))))
                            ->addAggregation($expectedIntegerFacetAggregation)),
                            $expectedIntegerFacetAggregation,
            // add global aggregation for "bar" filtered integer facet
                            (new GlobalAggregation(FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . 'bar'))
                            ->addAggregation((new Filter(FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME))
                            ->setFilter((new BoolQuery())
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
                                    ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'baz'))
                                // "baz" is range type so we expect a range filter
                                ->addFilter((new Range())
                                    ->addField(PageIndexMap::INTEGER_FACET_FACET_VALUE, [
                                        'gte' => 789,
                                    ])))))
                            ->addAggregation($expectedIntegerFacetAggregation)),
                            $expectedIntegerFacetAggregation,
            // add global aggregation for "baz" filtered integer facet
                            (new GlobalAggregation(FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . 'baz'))
                            ->addAggregation((new Filter(FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME))
                            ->setFilter((new BoolQuery())
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
                                    ->setTerm(PageIndexMap::INTEGER_FACET_FACET_VALUE, 456)))))
                            ->addAggregation($expectedIntegerFacetAggregation)),
        ];

        $parameters = [
            'foo-param' => 123,
            'bar-param' => 456,
            'baz-param' => [
                'min' => 789,
            ],
        ];

        return [$facetConfig, $expectedAggregations, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredCategoryFacetData(): array
    {
        $facetConfig = $this->createCategorySearchConfig();
        $expectedCategoryFacetAggregation = $this->getExpectedCategoryFacetAggregation();

        $expectedAggregations = [
            $expectedCategoryFacetAggregation,
            (new GlobalAggregation(FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . 'foo'))
                ->addAggregation((new Filter(FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME))
                    ->setFilter((new BoolQuery()))
                    ->addAggregation($expectedCategoryFacetAggregation)),
        ];

        $parameters = [
            'foo-param' => 'c1',
        ];

        return [$facetConfig, $expectedAggregations, $parameters];
    }

    /**
     * @return array
     */
    protected function createFilteredMixedFacetData(): array
    {
        $facetConfig = $this->createMixedSearchConfig();
        $expectedStringFacetAggregation = $this->getExpectedStringFacetAggregation();
        $expectedIntegerFacetAggregation = $this->getExpectedIntegerFacetAggregation();
        $expectedCategoryFacetAggregation = $this->getExpectedCategoryFacetAggregation();

        $expectedAggregations = [
            // add aggregation for string-facet
            $expectedStringFacetAggregation,
            // add global aggregation for "foo" filtered string facet
            (new GlobalAggregation(FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . 'foo'))
                ->addAggregation((new Filter(FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME))
                    ->setFilter((new BoolQuery())
                        ->addFilter((new Nested())
                            ->setPath(PageIndexMap::INTEGER_FACET)
                            ->setQuery((new BoolQuery())
                                ->addFilter((new Term())
                                    ->setTerm(PageIndexMap::INTEGER_FACET_FACET_NAME, 'bar'))
                                ->addFilter((new Term())
                                    ->setTerm(PageIndexMap::INTEGER_FACET_FACET_VALUE, 456))))
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::CATEGORY_ALL_PARENTS, 'c1')))
                    ->addAggregation($expectedStringFacetAggregation)),
            // add aggregation for integer-facet
            $expectedIntegerFacetAggregation,
            // add global aggregation for "bar" filtered integer facet
            (new GlobalAggregation(FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . 'bar'))
                ->addAggregation((new Filter(FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME))
                    ->setFilter((new BoolQuery())
                        ->addFilter((new Nested())
                            ->setPath(PageIndexMap::STRING_FACET)
                            ->setQuery((new BoolQuery())
                                ->addFilter((new Term())
                                    ->setTerm(PageIndexMap::STRING_FACET_FACET_NAME, 'foo'))
                                ->addFilter((new Term())
                                    ->setTerm(PageIndexMap::STRING_FACET_FACET_VALUE, 'asdf'))))
                        ->addFilter((new Term())
                            ->setTerm(PageIndexMap::CATEGORY_ALL_PARENTS, 'c1')))
                    ->addAggregation($expectedIntegerFacetAggregation)),
            // add aggregation for category
            $expectedCategoryFacetAggregation,
            // add global aggregation for "baz" filtered category
            (new GlobalAggregation(FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . 'baz'))
                ->addAggregation((new Filter(FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME))
                    ->setFilter((new BoolQuery())
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
                                    ->setTerm(PageIndexMap::INTEGER_FACET_FACET_VALUE, 456)))))
                    ->addAggregation($expectedCategoryFacetAggregation)),
        ];

        $parameters = [
            'foo-param' => 'asdf',
            'bar-param' => 456,
            'baz-param' => 'c1',
        ];

        return [$facetConfig, $expectedAggregations, $parameters];
    }
}
