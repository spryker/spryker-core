<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Extractor;

abstract class AbstractAggregationExtractor implements AggregationExtractorInterface
{

    /**
     * @param array $aggregation
     * @param string $fieldName
     *
     * @return array
     */
    abstract protected function extractData(array $aggregation, $fieldName);

    /**
     * @param array $aggregations
     * @param array $fields
     *
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
