<?php

namespace SprykerFeature\Sdk\Catalog\Model\Extractor;

/**
 * Class AbstractAggregationExtractor
 * @package SprykerFeature\Sdk\Catalog\Model\Extractor
 */
abstract class AbstractAggregationExtractor implements AggregationExtractorInterface
{
    /**
     * @param array $aggregation
     * @param $fieldName
     * @return mixed
     */
    abstract protected function extractData(array $aggregation, $fieldName);

    /**
     * @param array $aggregations
     * @param array $fields
     * @return array
     */
    public function extractDataFromAggregations(array $aggregations, array $fields)
    {
        $data = [];
        foreach ($fields as $field) {
            if (isset($aggregations[$field])) {
                $data = array_merge(
                    $data,
                    $this->extractData($aggregations[$field], $field)
                );
            }
        }

        return $data;
    }
}