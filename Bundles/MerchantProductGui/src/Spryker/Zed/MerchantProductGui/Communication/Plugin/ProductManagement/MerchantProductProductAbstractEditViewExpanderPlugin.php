<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication\Plugin\ProductManagement;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractEditViewExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductGui\Communication\MerchantProductGuiCommunicationFactory getFactory()
 */
class MerchantProductProductAbstractEditViewExpanderPlugin extends AbstractPlugin implements ProductAbstractEditViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands view data for abstract product with merchant data.
     *
     * @api
     *
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    public function expand(array $viewData): array
    {
        return $this->getFactory()->createMerchantProductViewDataExpander()->expandDataWithMerchant($viewData);
    }
}
