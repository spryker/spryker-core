<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGuiExtension\Dependency\Plugin;

/**
 * Provides extension capabilities for the raw data expanding during preparation of the MerchantTable
 */
interface MerchantTableDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands table row data.
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expand(array $item): array;
}
