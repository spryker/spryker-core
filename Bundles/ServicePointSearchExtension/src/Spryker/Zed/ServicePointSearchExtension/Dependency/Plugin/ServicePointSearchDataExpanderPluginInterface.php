<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ServicePointTransfer;

interface ServicePointSearchDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands service point search data with additional information.
     * - Called during service point search data mapping process.
     *
     * @api
     *
     * @param array<string, mixed> $searchData
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return array<string, mixed>
     */
    public function expand(array $searchData, ServicePointTransfer $servicePointTransfer): array;
}
