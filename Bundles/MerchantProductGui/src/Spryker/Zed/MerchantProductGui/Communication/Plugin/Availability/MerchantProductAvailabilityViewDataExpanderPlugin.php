<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication\Plugin\Availability;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityViewDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductGui\Communication\MerchantProductGuiCommunicationFactory getFactory()
 */
class MerchantProductAvailabilityViewDataExpanderPlugin extends AbstractPlugin implements AvailabilityViewDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands view data for product availability with merchant data.
     *
     * @api
     *
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    public function expand(array $viewData): array
    {
        $viewData['merchant'] = $this->getFactory()
            ->getMerchantProductFacade()
            ->findMerchant(
                (new MerchantProductCriteriaTransfer())->setIdProductAbstract($viewData['idProduct'])
            );

        return $viewData;
    }
}
