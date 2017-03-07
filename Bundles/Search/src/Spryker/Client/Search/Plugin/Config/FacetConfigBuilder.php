<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Config;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface;

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
     * @param string $paramName
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer|null
     */
    public function get($paramName)
    {
        return isset($this->facetConfigTransfers[$paramName]) ? $this->facetConfigTransfers[$paramName] : null;
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

        foreach ($this->facetConfigTransfers as $facetName => $facet) {
            if (array_key_exists($facetName, $requestParameters)) {
                $activeFacets[$facetName] = $facet;
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
