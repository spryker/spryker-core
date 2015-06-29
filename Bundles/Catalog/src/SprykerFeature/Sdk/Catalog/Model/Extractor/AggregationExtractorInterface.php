<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Sdk\Catalog\Model\Extractor;

/**
 * Class AbstractAggregationExtractor
 * @package SprykerFeature\Sdk\Catalog\Model\Extractor
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