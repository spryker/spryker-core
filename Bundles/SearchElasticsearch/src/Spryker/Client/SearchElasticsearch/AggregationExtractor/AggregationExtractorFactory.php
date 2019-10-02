<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Money\Plugin\MoneyPlugin;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig;

class AggregationExtractorFactory implements AggregationExtractorFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchElasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    public function create(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface
    {
        return $this->createByType($facetConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchElasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createByType(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface
    {
        switch ($facetConfigTransfer->getType()) {
            case SearchElasticsearchConfig::FACET_TYPE_RANGE:
                return $this->createRangeExtractor($facetConfigTransfer);

            case SearchElasticsearchConfig::FACET_TYPE_PRICE_RANGE:
                return $this->createPriceRangeExtractor($facetConfigTransfer);

            case SearchElasticsearchConfig::FACET_TYPE_CATEGORY:
                return $this->createCategoryExtractor($facetConfigTransfer);

            default:
                return $this->createFacetExtractor($facetConfigTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchElasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createRangeExtractor(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface
    {
        return new RangeExtractor($facetConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchElasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createPriceRangeExtractor(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface
    {
        return new PriceRangeExtractor($facetConfigTransfer, $this->createMoneyPlugin());
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchElasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createFacetExtractor(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface
    {
        return new FacetExtractor($facetConfigTransfer, $this->createFacetValueTransformerFactory());
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchElasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createCategoryExtractor(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface
    {
        return new CategoryExtractor($facetConfigTransfer);
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected function createMoneyPlugin(): MoneyPluginInterface
    {
        return new MoneyPlugin();
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\AggregationExtractor\FacetValueTransformerFactory
     */
    protected function createFacetValueTransformerFactory(): FacetValueTransformerFactoryInterface
    {
        return new FacetValueTransformerFactory();
    }
}
