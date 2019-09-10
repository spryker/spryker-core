<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Config;

use Generated\Shared\Transfer\SortConfigTransfer;

interface SortConfigBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SortConfigTransfer $sortConfigTransfer
     *
     * @return $this
     */
    public function addSort(SortConfigTransfer $sortConfigTransfer);

    /**
     * @param string $parameterName
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer|null
     */
    public function get($parameterName): ?SortConfigTransfer;

    /**
     * @return \Generated\Shared\Transfer\SortConfigTransfer[]
     */
    public function getAll(): array;

    /**
     * @param array $requestParameters
     *
     * @return string|null
     */
    public function getActiveParamName(array $requestParameters): ?string;

    /**
     * @param string $sortParamName
     *
     * @return string|null
     */
    public function getSortDirection($sortParamName): ?string;
}
