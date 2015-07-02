<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model\Extractor;

/**
 * Class AbstractAggregationExtractor
 * @package SprykerFeature\Client\Catalog\Service\Model\Extractor
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
