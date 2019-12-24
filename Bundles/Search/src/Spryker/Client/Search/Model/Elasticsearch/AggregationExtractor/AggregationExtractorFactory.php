<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Money\Plugin\MoneyPlugin;
use Spryker\Shared\Search\SearchConfig;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\AggregationExtractor\AggregationExtractorFactory` instead.
 */
class AggregationExtractorFactory implements AggregationExtractorFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    public function create(FacetConfigTransfer $facetConfigTransfer)
    {
        return $this->createByType($facetConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createByType(FacetConfigTransfer $facetConfigTransfer)
    {
        switch ($facetConfigTransfer->getType()) {
            case SearchConfig::FACET_TYPE_RANGE:
                return $this->createRangeExtractor($facetConfigTransfer);

            case SearchConfig::FACET_TYPE_PRICE_RANGE:
                return $this->createPriceRangeExtractor($facetConfigTransfer);

            case SearchConfig::FACET_TYPE_CATEGORY:
                return $this->createCategoryExtractor($facetConfigTransfer);

            default:
                return $this->createFacetExtractor($facetConfigTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createRangeExtractor(FacetConfigTransfer $facetConfigTransfer)
    {
        return new RangeExtractor($facetConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createPriceRangeExtractor(FacetConfigTransfer $facetConfigTransfer)
    {
        return new PriceRangeExtractor($facetConfigTransfer, $this->createMoneyPlugin());
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createFacetExtractor(FacetConfigTransfer $facetConfigTransfer)
    {
        return new FacetExtractor($facetConfigTransfer, $this->createFacetValueTransformerFactory());
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createCategoryExtractor(FacetConfigTransfer $facetConfigTransfer)
    {
        return new CategoryExtractor($facetConfigTransfer);
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected function createMoneyPlugin()
    {
        return new MoneyPlugin();
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\FacetValueTransformerFactory
     */
    protected function createFacetValueTransformerFactory()
    {
        return new FacetValueTransformerFactory();
    }
}
