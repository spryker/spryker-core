<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\FacetQueryExpanderPlugin;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class FacetResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{

    const NAME = 'facets';

    /**
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        $facetData = [];

        $facetConfig = $this
            ->getFactory()
            ->getSearchConfig()
            ->getFacetConfigBuilder();

        $aggregations = $searchResult->getAggregations();

        foreach ($facetConfig->getAll() as $facetName => $facetConfigTransfer) {
            $extractor = $this
                ->getFactory()
                ->createAggregationExtractorFactory()
                ->create($facetConfigTransfer);

            $aggregation = $this->getAggregationRawData($aggregations, $facetConfigTransfer);

            if ($aggregation) {
                $facetData[$facetName] = $extractor->extractDataFromAggregations($aggregation, $requestParameters);
            }
        }

        return $facetData;
    }

    /**
     * @param array $aggregations
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return array
     */
    protected function getAggregationRawData(array $aggregations, FacetConfigTransfer $facetConfigTransfer)
    {
        $fieldName = $facetConfigTransfer->getFieldName();
        $bucketName = FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . $facetConfigTransfer->getName();

        if (isset($aggregations[$bucketName])) {
            return $aggregations[$bucketName][FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME][$fieldName];
        }

        if (isset($aggregations[$fieldName])) {
            return $aggregations[$fieldName];
        }

        return [];
    }

}
