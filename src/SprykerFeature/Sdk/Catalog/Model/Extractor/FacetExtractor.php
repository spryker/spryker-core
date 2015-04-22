<?php

namespace SprykerFeature\Sdk\Catalog\Model\Extractor;

/**
 * Class FacetExtractor
 */
class FacetExtractor extends AbstractAggregationExtractor
{
    /**
     * @param array $aggregation
     * @param $fieldName
     * @return mixed
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