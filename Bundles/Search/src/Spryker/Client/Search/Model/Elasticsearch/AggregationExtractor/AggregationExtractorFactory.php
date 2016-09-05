<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;
use Spryker\Shared\Library\Currency\CurrencyManager;

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
            case FacetConfigBuilder::TYPE_RANGE:
                return $this->createRangeExtractor($facetConfigTransfer);

            case FacetConfigBuilder::TYPE_PRICE_RANGE:
                return $this->createPriceRangeExtractor($facetConfigTransfer);

            case FacetConfigBuilder::TYPE_CATEGORY:
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
        return new PriceRangeExtractor($facetConfigTransfer, $this->createCurrencyManager());
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createFacetExtractor(FacetConfigTransfer $facetConfigTransfer)
    {
        return new FacetExtractor($facetConfigTransfer);
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
     * @return \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected function createCurrencyManager()
    {
        return CurrencyManager::getInstance();
    }

}
