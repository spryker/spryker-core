<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementExtension\Dependency\Plugin;

/**
 * Provides capabilities to expand product table data.
 */
interface ProductTableDataBulkExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands product table data.
     *
     * @api
     *
     * @param array<array<string, mixed>> $items
     * @param array<array<string, mixed>> $productData
     *
     * @return array<array<string, mixed>>
     */
    public function expandTableData(array $items, array $productData): array;
}
