<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Plugin\ProductManagement;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractEditViewExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductApprovalGui\Communication\ProductApprovalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductApprovalGui\ProductApprovalGuiConfig getConfig()
 */
class ProductApprovalProductAbstractEditViewExpanderPlugin extends AbstractPlugin implements ProductAbstractEditViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands view data with abstract product approval status data.
     *
     * @api
     *
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    public function expand(array $viewData): array
    {
        return $this->getFactory()
            ->createProductApprovalProductAbstractEditViewExpander()
            ->expand($viewData);
    }
}
