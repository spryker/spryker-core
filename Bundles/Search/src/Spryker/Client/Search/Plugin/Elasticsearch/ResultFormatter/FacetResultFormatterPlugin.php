<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class FacetResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        return [
            'facets' => $this->extractFacetDataFromResult($searchResult, $requestParameters),
        ];
    }

    /**
     * @param \Elastica\ResultSet $resultSet
     * @param array $requestParameters
     *
     * @return array
     */
    protected function extractFacetDataFromResult(ResultSet $resultSet, array $requestParameters)
    {
        $facetData = [];

        $facetConfig = $this
            ->getFactory()
            ->getSearchConfig()
            ->getFacetConfigBuilder();

        $aggregations = $resultSet->getAggregations();

        foreach ($facetConfig->getAll() as $facetName => $facetConfigTransfer) {
            $fieldName = $facetConfigTransfer->getFieldName();
            if (!isset($aggregations[$fieldName])) {
                continue;
            }

            $extractor = $this
                ->getFactory()
                ->createAggregationExtractorFactory()
                ->create($facetConfigTransfer);

            $facetData[$facetName] = $extractor->extractDataFromAggregations($aggregations[$fieldName], $requestParameters);
        }

        return $facetData;
    }

}
