<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query\BoolQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group FacetQueryExpanderPluginQueryTest
 * Add your own group annotations below this line
 */
class FacetQueryExpanderPluginQueryTest extends AbstractFacetQueryExpanderPluginQueryTest
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
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createMultiStringFacetData()
    {
        $searchConfig = $this->createMultiStringSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createIntegerFacetData()
    {
        $searchConfig = $this->createIntegerSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createMultiIntegerFacetData()
    {
        $searchConfig = $this->createMultiIntegerSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createCategoryFacetData()
    {
        $searchConfig = $this->createCategorySearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createMultiCategoryFacetData()
    {
        $searchConfig = $this->createMultiCategorySearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }

    /**
     * @return array
     */
    protected function createMixedFacetData()
    {
        $searchConfig = $this->createMixedSearchConfig();
        $expectedQuery = new BoolQuery();

        return [$searchConfig, $expectedQuery];
    }
}
