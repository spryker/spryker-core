<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model\Extractor;

class FacetExtractor extends AbstractAggregationExtractor
{

    /**
     * @param array $aggregation
     * @param string $fieldName
     *
     * @return array
     */
    protected function extractData(array $aggregation, $fieldName)
    {
        $facets = [];
        foreach ($aggregation[$fieldName . '-name']['buckets'] as $nameBucket) {
            $facetName = $nameBucket['key'];
            $facetValues = [];
            foreach ($nameBucket[$fieldName . '-value']['buckets'] as $valueBucket) {
                $facetValues[$valueBucket['key']] = $valueBucket['doc_count'];
            }
            $facets[$facetName] = $facetValues;
        }

        return $facets;
    }

}
