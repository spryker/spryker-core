<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCategoryStorageClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToLocaleClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToMoneyClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface;
use Spryker\Shared\SearchHttp\SearchHttpConfig;

class AggregationExtractorFactory implements AggregationExtractorFactoryInterface
{
    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToMoneyClientInterface
     */
    protected SearchHttpToMoneyClientInterface $moneyClient;

    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCategoryStorageClientInterface
     */
    protected SearchHttpToCategoryStorageClientInterface $categoryStorageClient;

    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToLocaleClientInterface
     */
    protected SearchHttpToLocaleClientInterface $localeClient;

    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface
     */
    protected SearchHttpToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToMoneyClientInterface $moneyClient
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCategoryStorageClientInterface $categoryStorageClient
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToLocaleClientInterface $localeClient
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface $storeClient
     */
    public function __construct(
        SearchHttpToMoneyClientInterface $moneyClient,
        SearchHttpToCategoryStorageClientInterface $categoryStorageClient,
        SearchHttpToLocaleClientInterface $localeClient,
        SearchHttpToStoreClientInterface $storeClient
    ) {
        $this->moneyClient = $moneyClient;
        $this->categoryStorageClient = $categoryStorageClient;
        $this->localeClient = $localeClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchHttp\AggregationExtractor\AggregationExtractorInterface
     */
    public function create(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface
    {
        return $this->createByType($facetConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchHttp\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createByType(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface
    {
        return match ($facetConfigTransfer->getType()) {
            SearchHttpConfig::FACET_TYPE_RANGE => $this->createRangeExtractor($facetConfigTransfer),
            SearchHttpConfig::FACET_TYPE_PRICE_RANGE => $this->createPriceRangeExtractor($facetConfigTransfer),
            SearchHttpConfig::FACET_TYPE_CATEGORY => $this->createCategoryExtractor($facetConfigTransfer),
            default => $this->createFacetExtractor($facetConfigTransfer),
        };
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchHttp\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createRangeExtractor(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface
    {
        return new RangeExtractor($facetConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchHttp\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createPriceRangeExtractor(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface
    {
        return new PriceRangeExtractor($facetConfigTransfer, $this->moneyClient);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchHttp\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createFacetExtractor(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface
    {
        return new FacetExtractor($facetConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchHttp\AggregationExtractor\AggregationExtractorInterface
     */
    protected function createCategoryExtractor(FacetConfigTransfer $facetConfigTransfer): AggregationExtractorInterface
    {
        return new CategoryExtractor(
            $facetConfigTransfer,
            $this->categoryStorageClient,
            $this->localeClient,
            $this->storeClient,
        );
    }
}
