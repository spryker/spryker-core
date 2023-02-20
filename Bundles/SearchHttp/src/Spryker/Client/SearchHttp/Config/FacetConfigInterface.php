<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Config;

use Generated\Shared\Transfer\FacetConfigTransfer;

interface FacetConfigInterface
{
    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return $this
     */
    public function addFacet(FacetConfigTransfer $facetConfigTransfer);

    /**
     * @return array<\Generated\Shared\Transfer\FacetConfigTransfer>
     */
    public function getAll(): array;

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return array<\Generated\Shared\Transfer\FacetConfigTransfer>
     */
    public function getActive(array $requestParameters): array;
}
