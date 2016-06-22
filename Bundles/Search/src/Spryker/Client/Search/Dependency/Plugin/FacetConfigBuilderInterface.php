<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Client\Search\Dependency\Plugin;

use Generated\Shared\Transfer\FacetConfigTransfer;

interface FacetConfigBuilderInterface
{

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return $this
     */
    public function addFacet(FacetConfigTransfer $facetConfigTransfer);

    /**
     * @param string $paramName
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer|null
     */
    public function get($paramName);

    /**
     * @return \Generated\Shared\Transfer\FacetConfigTransfer[]
     */
    public function getAll();

    /**
     * @return array
     */
    public function getParamNames();

    /**
     * @param array $requestParameters
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer[]
     */
    public function getActive(array $requestParameters);

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function getActiveParamNames(array $requestParameters);

}
