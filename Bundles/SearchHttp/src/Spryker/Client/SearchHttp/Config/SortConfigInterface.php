<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Config;

use Generated\Shared\Transfer\SortConfigTransfer;

interface SortConfigInterface
{
    /**
     * @param \Generated\Shared\Transfer\SortConfigTransfer $sortConfigTransfer
     *
     * @return $this
     */
    public function addSort(SortConfigTransfer $sortConfigTransfer);

    /**
     * @return array<\Generated\Shared\Transfer\SortConfigTransfer>
     */
    public function getAll(): array;

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return string|null
     */
    public function getActiveParamName(array $requestParameters): ?string;

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return string|null
     */
    public function getActiveCleanedParamName(array $requestParameters): ?string;

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return string|null
     */
    public function getSortDirection(array $requestParameters): ?string;
}
