<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Generated\Shared\Search\Catalog\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\Exception\MissingFacetAggregationException;

class FacetAggregationFactory implements FacetAggregationFactoryInterface
{

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    public function create(FacetConfigTransfer $facetConfigTransfer)
    {
        return $this->createByFacetValueType($facetConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @throws \Spryker\Client\Search\Exception\MissingFacetAggregationException
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createByFacetValueType(FacetConfigTransfer $facetConfigTransfer)
    {
        $type = $this->getFacetValueType($facetConfigTransfer);

        switch ($type) {
            case 'string':
                return $this->createStringFacetAggregation($facetConfigTransfer);

            case 'integer':
            case 'float':
                return $this->createNumericFacetAggregation($facetConfigTransfer);

            default:
                throw new MissingFacetAggregationException(sprintf(
                    'Missing facet aggregation for type "%s" in field "%s".',
                    $type,
                    $facetConfigTransfer->getFieldName()
                ));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return string|null
     */
    protected function getFacetValueType(FacetConfigTransfer $facetConfigTransfer)
    {
        $pageIndexMap = new PageIndexMap();

        return $pageIndexMap->getType($facetConfigTransfer->getFieldName() . '.facet-value');
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createStringFacetAggregation(FacetConfigTransfer $facetConfigTransfer)
    {
        return new StringFacetAggregation($facetConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createNumericFacetAggregation(FacetConfigTransfer $facetConfigTransfer)
    {
        return new NumericFacetAggregation($facetConfigTransfer);
    }

}
