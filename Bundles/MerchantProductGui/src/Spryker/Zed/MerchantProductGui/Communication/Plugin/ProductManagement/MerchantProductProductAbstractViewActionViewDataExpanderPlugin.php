<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication\Plugin\ProductManagement;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractViewActionViewDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductGui\Communication\MerchantProductGuiCommunicationFactory getFactory()
 */
class MerchantProductProductAbstractViewActionViewDataExpanderPlugin extends AbstractPlugin implements ProductAbstractViewActionViewDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands view data for abstract product with merchant data.
     *
     * @api
     *
     * @phpstan-param array<string, mixed> $viewData
     *
     * @phpstan-return array<string, mixed>
     *
     * @param array $viewData
     *
     * @return array
     */
    public function expand(array $viewData): array
    {
        if (!isset($viewData['currentProduct']['id_product_abstract'])) {
            return $viewData;
        }

        return $this->getFactory()
            ->createMerchantProductViewDataExpander()
            ->expandDataWithMerchantByIdProductAbstract($viewData, (int)$viewData['currentProduct']['id_product_abstract']);
    }
}
