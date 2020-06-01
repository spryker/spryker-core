<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication\Plugin\Availability;

use Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityListDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductGui\Communication\MerchantProductGuiCommunicationFactory getFactory()
 */
class MerchantProductAvailabilityListDataExpanderPlugin extends AbstractPlugin implements AvailabilityListDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands view data for list of product availabilities with merchants data.
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
            ->createMerchantProductListDataExpander()
            ->expandData($viewData);
    }
}
