<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

/**
 * @deprecated Use {@link \Spryker\Client\SearchElasticsearch\AggregationExtractor\AggregationExtractorInterface} instead.
 */
interface AggregationExtractorInterface
{
    /**
     * @param array<string, mixed> $aggregations
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters);
}
