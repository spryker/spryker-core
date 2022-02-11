<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementExtension\Dependency\Plugin;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableDataBulkExpanderPluginInterface} instead
 *
 * Use this plugin to extend product table rows with additional data.
 */
interface ProductTableDataExpanderPluginInterface
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
