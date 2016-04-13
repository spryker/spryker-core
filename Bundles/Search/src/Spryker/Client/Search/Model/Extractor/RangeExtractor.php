<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Extractor;

class RangeExtractor extends AbstractAggregationExtractor
{

    /**
     * @param array $aggregation
     * @param string $fieldName
     *
     * @return array
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
