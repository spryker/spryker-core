<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication\Plugin\AvailabilityGui;

use Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityViewActionViewDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductGui\Communication\MerchantProductGuiCommunicationFactory getFactory()
 */
class MerchantProductAvailabilityViewActionViewDataExpanderPlugin extends AbstractPlugin implements AvailabilityViewActionViewDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands view data for product availability with merchant data.
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
        if (!isset($viewData['idProduct'])) {
            return $viewData;
        }

        return $this->getFactory()
            ->createMerchantProductViewDataExpander()
            ->expandDataWithMerchantByIdProductAbstract($viewData, (int)$viewData['idProduct']);
    }
}
