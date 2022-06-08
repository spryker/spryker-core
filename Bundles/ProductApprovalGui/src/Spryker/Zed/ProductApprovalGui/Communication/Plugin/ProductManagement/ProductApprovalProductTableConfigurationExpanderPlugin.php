<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Plugin\ProductManagement;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableConfigurationExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductApprovalGui\Communication\ProductApprovalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductApprovalGui\ProductApprovalGuiConfig getConfig()
 */
class ProductApprovalProductTableConfigurationExpanderPlugin extends AbstractPlugin implements ProductTableConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `ProductTable` configuration with abstract product approval status column.
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandTableConfiguration(TableConfiguration $config): TableConfiguration
    {
        return $this->getFactory()
            ->createProductApprovalProductTableConfigurationExpander()
            ->expandTableConfiguration($config);
    }
}
