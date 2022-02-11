<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationGui\Communication\Plugin\ProductManagement;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableDataBulkExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationGui\Communication\ProductConfigurationGuiCommunicationFactory getFactory()
 */
class ProductConfigurationProductTableDataBulkExpanderPlugin extends AbstractPlugin implements ProductTableDataBulkExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if product abstract has at least one product concrete with product configuration.
     * - Expands product items with configurable product type if has or do nothing otherwise.
     *
     * @api
     *
     * @param array<array<string, mixed>> $items
     * @param array<array<string, mixed>> $productData
     *
     * @return array<array<string, mixed>>
     */
    public function expandTableData(array $items, array $productData): array
    {
        return $this->getFactory()
            ->createProductConfigurationTableDataExpander()
            ->expandProductItemsWithProductData($items, $productData);
    }
}
