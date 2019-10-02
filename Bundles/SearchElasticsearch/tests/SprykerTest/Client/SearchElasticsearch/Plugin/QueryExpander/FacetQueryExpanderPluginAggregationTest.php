<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\QueryExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
 * @group QueryExpander
 * @group FacetQueryExpanderPluginAggregationTest
 * Add your own group annotations below this line
 */
class FacetQueryExpanderPluginAggregationTest extends AbstractFacetQueryExpanderPluginAggregationTest
{
    /**
     * @return array
     */
    public function facetQueryExpanderDataProvider(): array
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
     * @return array
     */
    protected function createStringFacetData(): array
    {
        $searchConfig = $this->createStringSearchConfig();

        $expectedAggregations = [
            $this->getExpectedStringFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createMultiStringFacetData(): array
    {
        $searchConfig = $this->createMultiStringSearchConfig();

        $expectedAggregations = [
            $this->getExpectedStringFacetAggregation(),
            $this->getExpectedStringFacetAggregation(),
            $this->getExpectedStringFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createIntegerFacetData(): array
    {
        $searchConfig = $this->createIntegerSearchConfig();

        $expectedAggregations = [
            $this->getExpectedIntegerFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createMultiIntegerFacetData(): array
    {
        $searchConfig = $this->createMultiIntegerSearchConfig();

        $expectedAggregations = [
            $this->getExpectedIntegerFacetAggregation(),
            $this->getExpectedIntegerFacetAggregation(),
            $this->getExpectedIntegerFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createCategoryFacetData(): array
    {
        $facetConfig = $this->createCategorySearchConfig();

        $expectedAggregations = [
            $this->getExpectedCategoryFacetAggregation(),
        ];

        return [$facetConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createMultiCategoryFacetData(): array
    {
        $searchConfig = $this->createMultiCategorySearchConfig();

        $expectedAggregations = [
            $this->getExpectedCategoryFacetAggregation(),
            $this->getExpectedCategoryFacetAggregation(),
            $this->getExpectedCategoryFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createMixedFacetData(): array
    {
        $searchConfig = $this->createMixedSearchConfig();

        $expectedAggregations = [
            $this->getExpectedStringFacetAggregation(),
            $this->getExpectedIntegerFacetAggregation(),
            $this->getExpectedCategoryFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }
}
