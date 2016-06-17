<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
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
        return self::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return mixed
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
            $fieldName = $facetConfigTransfer->getFieldName();
            if (!isset($aggregations[$fieldName])) {
                continue;
            }

            $extractor = $this
                ->getFactory()
                ->createAggregationExtractorFactory()
                ->create($facetConfigTransfer);

            $aggregation = $this->getAggregationRawData($aggregations, $facetConfigTransfer);

            $facetData[$facetName] = $extractor->extractDataFromAggregations($aggregation, $requestParameters);
        }

        return $facetData;
    }

    /**
     * @param array $aggregations
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return array
     */
    protected function getAggregationRawData(array $aggregations, $facetConfigTransfer)
    {
        $facetName = $facetConfigTransfer->getName();
        $fieldName = $facetConfigTransfer->getFieldName();

        if ($facetConfigTransfer->getIsMultiValued() === true) {
            $aggregation = $aggregations[FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . $facetName][FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME][$fieldName];
        } else {
            $aggregation = $aggregations[$fieldName];
        }

        return $aggregation;
    }

}
