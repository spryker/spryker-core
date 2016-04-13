<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Extractor;

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
