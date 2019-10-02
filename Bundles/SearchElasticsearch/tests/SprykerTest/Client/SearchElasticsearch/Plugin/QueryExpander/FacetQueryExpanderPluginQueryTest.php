<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query\BoolQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
 * @group QueryExpander
 * @group FacetQueryExpanderPluginQueryTest
 * Add your own group annotations below this line
 */
class FacetQueryExpanderPluginQueryTest extends AbstractFacetQueryExpanderPluginQueryTest
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
        $facetConfig = $this->createStringSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$facetConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createMultiStringFacetData(): array
    {
        $facetConfig = $this->createMultiStringSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$facetConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createIntegerFacetData(): array
    {
        $facetConfig = $this->createIntegerSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$facetConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createMultiIntegerFacetData(): array
    {
        $facetConfig = $this->createMultiIntegerSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$facetConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createCategoryFacetData(): array
    {
        $facetConfig = $this->createCategorySearchConfig();
        $expectedQuery = new BoolQuery();

        return [$facetConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createMultiCategoryFacetData(): array
    {
        $facetConfig = $this->createMultiCategorySearchConfig();
        $expectedQuery = new BoolQuery();

        return [$facetConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createMixedFacetData(): array
    {
        $facetConfig = $this->createMixedSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$facetConfig, $expectedQuery];
    }
}
