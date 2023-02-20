<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class FacetSearchHttpResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface
{
    /**
     * @var string
     */
    public const NAME = 'facets';

    /**
     * @var string
     */
    protected const PATTERN_FACET_NAME_PRICE = '/^price-.+/';

    /**
     * @var string
     */
    protected const HTTP_FACET_NAME_PRICE = 'price';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     * - Formats facets in result.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function formatResult($searchResult, array $requestParameters = []): array
    {
        $facetData = [];

        $facetConfig = $this->getFactory()->getSearchConfig()->getFacetConfig();
        $facetAggregations = $searchResult->getFacets();

        foreach ($facetConfig->getAll() as $facetName => $facetConfigTransfer) {
            $extractor = $this
                ->getFactory()
                ->createAggregationExtractorFactory()
                ->create($facetConfigTransfer);

            $aggregation = $this->getAggregationData($facetAggregations, $facetName);

            if ($aggregation) {
                $facetData[$facetName] = $extractor->extractDataFromAggregations($aggregation, $requestParameters);
            }
        }

        return $facetData;
    }

    /**
     * @param array<string, mixed> $facetAggregations
     * @param string $facetName
     *
     * @return array<string, mixed>
     */
    protected function getAggregationData(array $facetAggregations, string $facetName): array
    {
        if ($this->isPriceFacet($facetName, $facetAggregations)) {
            return $facetAggregations[static::HTTP_FACET_NAME_PRICE];
        }

        if (isset($facetAggregations[$facetName])) {
            return $facetAggregations[$facetName];
        }

        return [];
    }

    /**
     * @param string $facetName
     * @param array<string, mixed> $facetAggregations
     *
     * @return bool
     */
    protected function isPriceFacet(string $facetName, array $facetAggregations): bool
    {
        return (preg_match(static::PATTERN_FACET_NAME_PRICE, $facetName)
            && isset($facetAggregations[static::HTTP_FACET_NAME_PRICE]));
    }
}
