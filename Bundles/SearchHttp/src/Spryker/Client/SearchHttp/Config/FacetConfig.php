<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Config;

use Generated\Shared\Transfer\FacetConfigTransfer;

class FacetConfig implements FacetConfigInterface
{
    /**
     * @var array<\Generated\Shared\Transfer\FacetConfigTransfer>
     */
    protected array $facetConfigTransfers = [];

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return $this
     */
    public function addFacet(FacetConfigTransfer $facetConfigTransfer)
    {
        $this->assertFacetConfigTransfer($facetConfigTransfer);

        $this->facetConfigTransfers[$facetConfigTransfer->getName()] = $facetConfigTransfer;

        return $this;
    }

    /**
     * @return array<\Generated\Shared\Transfer\FacetConfigTransfer>
     */
    public function getAll(): array
    {
        return $this->facetConfigTransfers;
    }

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return array<\Generated\Shared\Transfer\FacetConfigTransfer>
     */
    public function getActive(array $requestParameters): array
    {
        $activeFacets = [];

        foreach ($this->facetConfigTransfers as $facetName => $facetConfigTransfer) {
            if (isset($requestParameters[$facetConfigTransfer->getParameterName()])) {
                $activeFacets[$facetName] = $facetConfigTransfer;
            }
        }

        return $activeFacets;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return void
     */
    protected function assertFacetConfigTransfer(FacetConfigTransfer $facetConfigTransfer): void
    {
        $facetConfigTransfer
            ->requireName()
            ->requireFieldName()
            ->requireParameterName()
            ->requireType();
    }
}
