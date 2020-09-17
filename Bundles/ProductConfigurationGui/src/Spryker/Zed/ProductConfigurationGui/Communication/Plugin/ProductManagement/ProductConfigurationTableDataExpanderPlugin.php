<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationGui\Communication\Plugin\ProductManagement;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationGui\Communication\ProductConfigurationGuiCommunicationFactory getFactory()
 */
class ProductConfigurationTableDataExpanderPlugin extends AbstractPlugin implements ProductTableDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks is product abstract has at least one product concrete with product configuration.
     * - Expands product items with configurable product type if has or do nothing otherwise.
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expand(array $item): array
    {
        return $this->getFactory()
                ->createProductConfigurationTableDataExpander()->expandProductItemWithProductConfigurationType($item);
    }
}
