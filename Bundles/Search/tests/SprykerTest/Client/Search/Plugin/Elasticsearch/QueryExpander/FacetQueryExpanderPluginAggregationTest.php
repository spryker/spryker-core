<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\QueryExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group FacetQueryExpanderPluginAggregationTest
 * Add your own group annotations below this line
 */
class FacetQueryExpanderPluginAggregationTest extends AbstractFacetQueryExpanderPluginAggregationTest
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
     * @return array
     */
    protected function createStringFacetData()
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
    protected function createMultiStringFacetData()
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
    protected function createIntegerFacetData()
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
    protected function createMultiIntegerFacetData()
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
    protected function createCategoryFacetData()
    {
        $searchConfig = $this->createCategorySearchConfig();

        $expectedAggregations = [
            $this->getExpectedCategoryFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    /**
     * @return array
     */
    protected function createMultiCategoryFacetData()
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
    protected function createMixedFacetData()
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
