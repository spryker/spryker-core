<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;

abstract class AbstractAggregationExtractor implements AggregationExtractorInterface
{
    public const PATH_SEPARATOR = '.';

    public const DOC_COUNT = 'doc_count';

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return string
     */
    protected function getNestedFieldName(FacetConfigTransfer $facetConfigTransfer): string
    {
        $nestedFieldName = $facetConfigTransfer->getFieldName();

        if ($facetConfigTransfer->getAggregationParams()) {
            $nestedFieldName = $this->addNestedFieldPrefix(
                $nestedFieldName,
                $facetConfigTransfer->getName()
            );
        }

        return $nestedFieldName;
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     *
     * @return string
     */
    protected function addNestedFieldPrefix(string $fieldName, string $nestedFieldName): string
    {
        return sprintf('%s%s%s', $fieldName, static::PATH_SEPARATOR, $nestedFieldName);
    }
}
