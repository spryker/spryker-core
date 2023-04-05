<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Builder;

use Generated\Shared\Transfer\FacetConfigTransfer;

interface FacetConfigBuilderInterface
{
    /**
     * @param string $facetName
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    public function buildRangeFacetConfigTransfer(string $facetName): FacetConfigTransfer;

    /**
     * @param string $facetName
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    public function buildValueFacetConfigTransfer(string $facetName): FacetConfigTransfer;
}
