<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Builder;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Shared\SearchHttp\SearchHttpConfig;

class FacetConfigBuilder implements FacetConfigBuilderInterface
{
    /**
     * @param string $facetName
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    public function buildRangeFacetConfigTransfer(string $facetName): FacetConfigTransfer
    {
        return (new FacetConfigTransfer())
            ->setName($facetName)
            ->setParameterName($this->normalizeParameterName($facetName))
            ->setFieldName($facetName)
            ->setType(SearchHttpConfig::FACET_TYPE_RANGE);
    }

    /**
     * @param string $facetName
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    public function buildValueFacetConfigTransfer(string $facetName): FacetConfigTransfer
    {
        return (new FacetConfigTransfer())
            ->setName($facetName)
            ->setParameterName($this->normalizeParameterName($facetName))
            ->setFieldName($facetName)
            ->setIsMultiValued(true)
            ->setType(SearchHttpConfig::FACET_TYPE_ENUMERATION);
    }

    /**
     * This method replaces "." and " " (Space) to "_" because PHP does this conversion for request params.
     * More info here: https://www.php.net/manual/en/language.variables.external.php
     *
     * @param string $parameterName
     *
     * @return string
     */
    protected function normalizeParameterName(string $parameterName): string
    {
        return str_replace([' ', '.'], '_', $parameterName);
    }
}
