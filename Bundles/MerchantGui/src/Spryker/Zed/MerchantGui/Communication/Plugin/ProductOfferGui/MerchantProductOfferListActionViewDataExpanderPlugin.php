<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Plugin\ProductOfferGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferListActionViewDataExpanderPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\MerchantProductOfferGui\Communication\Plugin\ProductOfferGui\MerchantProductOfferListActionViewDataExpanderPlugin} instead
 *
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantGui\MerchantGuiConfig getConfig()
 */
class MerchantProductOfferListActionViewDataExpanderPlugin extends AbstractPlugin implements ProductOfferListActionViewDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands product offer view action data with merchant data.
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
