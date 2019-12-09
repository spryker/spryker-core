<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

use Generated\Shared\Transfer\SortConfigTransfer;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Config\SortConfigInterface` instead.
 */
interface SortConfigBuilderInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SortConfigTransfer $sortConfigTransfer
     *
     * @return $this
     */
    public function addSort(SortConfigTransfer $sortConfigTransfer);

    /**
     * @api
     *
     * @param string $parameterName
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer|null
     */
    public function get($parameterName);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer[]
     */
    public function getAll();

    /**
     * @api
     *
     * @param array $requestParameters
     *
     * @return string|null
     */
    public function getActiveParamName(array $requestParameters);

    /**
     * @api
     *
     * @param string $sortParamName
     *
     * @return string|null
     */
    public function getSortDirection($sortParamName);
}
