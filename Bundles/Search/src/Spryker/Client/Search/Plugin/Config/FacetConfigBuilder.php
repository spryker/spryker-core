<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Config;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Plugin\Config\FacetConfigBuilder` instead.
 */
class FacetConfigBuilder extends AbstractPlugin implements FacetConfigBuilderInterface
{
    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer[]
     */
    protected $facetConfigTransfers = [];

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
     * @param string $facetName
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer|null
     */
    public function get($facetName)
    {
        return isset($this->facetConfigTransfers[$facetName]) ? $this->facetConfigTransfers[$facetName] : null;
    }

    /**
     * @return \Generated\Shared\Transfer\FacetConfigTransfer[]
     */
    public function getAll()
    {
        return $this->facetConfigTransfers;
    }

    /**
     * @return array
     */
    public function getParamNames()
    {
        return array_keys($this->facetConfigTransfers);
    }

    /**
     * @param array $requestParameters
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer[]
     */
    public function getActive(array $requestParameters)
    {
        $activeFacets = [];

        foreach ($this->facetConfigTransfers as $facetName => $facetConfigTransfer) {
            if (array_key_exists($facetConfigTransfer->getParameterName(), $requestParameters)) {
                $activeFacets[$facetName] = $facetConfigTransfer;
            }
        }

        return $activeFacets;
    }

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function getActiveParamNames(array $requestParameters)
    {
        return array_keys($this->getActive($requestParameters));
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return void
     */
    protected function assertFacetConfigTransfer(FacetConfigTransfer $facetConfigTransfer)
    {
        $facetConfigTransfer
            ->requireName()
            ->requireFieldName()
            ->requireParameterName()
            ->requireType();
    }
}
