<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Extractor;

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
