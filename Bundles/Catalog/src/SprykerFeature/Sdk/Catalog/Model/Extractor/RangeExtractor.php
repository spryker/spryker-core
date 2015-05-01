<?php

namespace SprykerFeature\Sdk\Catalog\Model\Extractor;

/**
 * Class RangeExtractor
 * @package SprykerFeature\Sdk\Catalog\Model\Extractor
 */
class RangeExtractor extends AbstractAggregationExtractor
{
    /**
     * @param array $aggregation
     * @param $fieldName
     * @return mixed
     */
    protected function extractData(array $aggregation, $fieldName)
    {
        $ranges = [];
        foreach ($aggregation[$fieldName . '-name']['buckets'] as $nameBucket) {
            $rangeName = $nameBucket['key'];
            $ranges[$rangeName] = [];
            if (isset($nameBucket[$fieldName . '-min'])) {
                $ranges[$rangeName]['min'] = $nameBucket[$fieldName . '-min']['value'];
            }
            if (isset($nameBucket[$fieldName . '-max'])) {
                $ranges[$rangeName]['max'] = $nameBucket[$fieldName . '-max']['value'];
            }
        }

        return $ranges;
    }
}