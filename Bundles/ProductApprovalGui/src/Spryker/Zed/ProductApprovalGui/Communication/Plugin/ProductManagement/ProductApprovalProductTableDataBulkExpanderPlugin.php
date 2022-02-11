<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Plugin\ProductManagement;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableDataBulkExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductApprovalGui\Communication\ProductApprovalGuiCommunicationFactory getFactory()
 */
class ProductApprovalProductTableDataBulkExpanderPlugin extends AbstractPlugin implements ProductTableDataBulkExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands product table items with abstract product approval status.
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
            ->createProductApprovalProductTableDataExpander()
            ->expandTableData($items, $productData);
    }
}
