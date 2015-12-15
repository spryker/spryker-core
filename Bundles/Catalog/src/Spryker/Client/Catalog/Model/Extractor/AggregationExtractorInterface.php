<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Catalog\Model\Extractor;

/**
 * Class AbstractAggregationExtractor
 */

interface AggregationExtractorInterface
{

    /**
     * @param array $aggregations
     * @param array $fields
     *
     * @return array
     */
    public function extractDataFromAggregations(array $aggregations, array $fields);

}
