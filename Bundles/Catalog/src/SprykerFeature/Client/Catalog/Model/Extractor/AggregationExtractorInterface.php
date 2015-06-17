<?php

namespace SprykerFeature\Client\Catalog\Model\Extractor;

/**
 * Class AbstractAggregationExtractor
 * @package SprykerFeature\Client\Catalog\Model\Extractor
 */
interface AggregationExtractorInterface
{
    /**
     * @param array $aggregations
     * @param array $fields
     * @return array
     */
    public function extractDataFromAggregations(array $aggregations, array $fields);
}