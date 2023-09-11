<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Generated\Shared\Transfer\FacetConfigTransfer;
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
    protected const AGGREGATION_NAME_PRICE = 'price';

    /**
     * @var string
     */
    protected const KEY_FROM = 'from';

    /**
     * @var string
     */
    protected const KEY_TO = 'to';

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

        foreach ($searchResult->getFacets() as $aggregationName => $aggregation) {
            $facetConfigTransfer = $this->getFacetConfig($aggregationName, $aggregation);

            $extractor = $this
                ->getFactory()
                ->createAggregationExtractorFactory()
                ->create($facetConfigTransfer);

            $facetData[$facetConfigTransfer->getName()] = $extractor->extractDataFromAggregations($aggregation, $requestParameters);
        }

        return $facetData;
    }

    /**
     * @param string $aggregationName
     * @param array<mixed> $aggregation
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected function getFacetConfig(string $aggregationName, array $aggregation): FacetConfigTransfer
    {
        $facetConfigTransfers = $this->getFactory()->getSearchConfig()->getFacetConfig()->getAll();

        if ($aggregationName === static::AGGREGATION_NAME_PRICE) {
            foreach ($facetConfigTransfers as $facetConfigTransfer) {
                if ($this->isPriceFacet($facetConfigTransfer->getNameOrFail())) {
                    return $facetConfigTransfer;
                }
            }
        }

        foreach ($facetConfigTransfers as $facetConfigTransfer) {
            if ($facetConfigTransfer->getName() === $aggregationName) {
                return $facetConfigTransfer;
            }
        }

        if ($this->isRangeAggregation($aggregation)) {
            return $this->getFactory()->createFacetConfigBuilder()->buildRangeFacetConfigTransfer($aggregationName);
        }

        return $this->getFactory()->createFacetConfigBuilder()->buildValueFacetConfigTransfer($aggregationName);
    }

    /**
     * @param string $facetName
     *
     * @return bool
     */
    protected function isPriceFacet(string $facetName): bool
    {
        return (bool)preg_match(static::PATTERN_FACET_NAME_PRICE, $facetName);
    }

    /**
     * @param array<mixed> $aggregation
     *
     * @return bool
     */
    protected function isRangeAggregation(array $aggregation): bool
    {
        return isset($aggregation[static::KEY_FROM]) || isset($aggregation[static::KEY_TO]);
    }
}
