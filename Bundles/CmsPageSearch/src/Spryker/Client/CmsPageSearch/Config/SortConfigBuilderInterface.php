<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch\Config;

use Generated\Shared\Transfer\SortConfigTransfer;

interface SortConfigBuilderInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\SortConfigTransfer $sortConfigTransfer
     *
     * @return $this
     */
    public function addSort(SortConfigTransfer $sortConfigTransfer): self;

    /**
     * @api
     *
     * @param string|null $parameterName
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer|null
     */
    public function get(?string $parameterName): ?SortConfigTransfer;

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer[]
     */
    public function getAll(): array;

    /**
     * @api
     *
     * @param array $requestParameters
     *
     * @return string|null
     */
    public function getActiveParamName(array $requestParameters): ?string;

    /**
     * @api
     *
     * @param string|null $sortParamName
     *
     * @return string|null
     */
    public function getSortDirection(?string $sortParamName): ?string;
}
