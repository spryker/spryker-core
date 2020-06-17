<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Plugin\AvailabilityGui;

use Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityListActionViewDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantGui\MerchantGuiConfig getConfig()
 */
class MerchantAvailabilityListActionViewDataExpanderPlugin extends AbstractPlugin implements AvailabilityListActionViewDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands view data for list of product availabilities with merchants data.
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
        return $this->getFactory()
            ->createMerchantListDataExpander()
            ->expandData($viewData);
    }
}
