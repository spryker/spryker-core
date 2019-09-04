<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\FacetConfigTransfer;

interface FacetConfigBuilderInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return $this
     */
    public function addFacet(FacetConfigTransfer $facetConfigTransfer);

    /**
     * @api
     *
     * @param string $facetName
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer|null
     */
    public function get($facetName);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer[]
     */
    public function getAll();

    /**
     * @api
     *
     * @return array
     */
    public function getParamNames();

    /**
     * @api
     *
     * @param array $requestParameters
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer[]
     */
    public function getActive(array $requestParameters);

    /**
     * @api
     *
     * @param array $requestParameters
     *
     * @return array
     */
    public function getActiveParamNames(array $requestParameters);
}

class_alias(FacetConfigBuilderInterface::class, 'Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface', false);
