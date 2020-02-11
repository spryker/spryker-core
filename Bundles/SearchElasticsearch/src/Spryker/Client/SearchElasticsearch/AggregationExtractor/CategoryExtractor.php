<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\AggregationExtractor;

use ArrayObject;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\FacetSearchResultValueTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class CategoryExtractor implements AggregationExtractorInterface
{
    protected const DOC_COUNT = 'doc_count';
    protected const KEY_BUCKETS = 'buckets';
    protected const KEY_KEY = 'key';

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
    }

    /**
     * @param array $aggregations
     * @param array $requestParameters
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters): TransferInterface
    {
        $name = $this->facetConfigTransfer->getName();

        $facetResultValueTransfers = $this->extractFacetData($aggregations);

        $facetResultTransfer = new FacetSearchResultTransfer();
        $facetResultTransfer
            ->setName($name)
            ->setValues($facetResultValueTransfers)
            ->setConfig(clone $this->facetConfigTransfer);

        if (isset($requestParameters[$name])) {
            $facetResultTransfer->setActiveValue($requestParameters[$name]);
        }

        return $facetResultTransfer;
    }

    /**
     * @param array $aggregation
     *
     * @return \ArrayObject
     */
    protected function extractFacetData(array $aggregation): ArrayObject
    {
        $facetValues = new ArrayObject();
        foreach ($aggregation[static::KEY_BUCKETS] as $bucket) {
            $facetResultValueTransfer = new FacetSearchResultValueTransfer();
            $facetResultValueTransfer
                ->setValue($bucket[static::KEY_KEY])
                ->setDocCount($bucket[static::DOC_COUNT]);

            $facetValues->append($facetResultValueTransfer);
        }

        return $facetValues;
    }
}
