<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication\Plugin\Availability;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityListDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductGui\Communication\MerchantProductGuiCommunicationFactory getFactory()
 */
class MerchantProductAvailabilityListDataExpanderPlugin extends AbstractPlugin implements AvailabilityListDataExpanderPluginInterface
{
    protected const URL_PARAM_ID_PRODUCT = 'id-merchant';

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
        $viewData['idMerchant'] = $this->getFactory()
            ->getRequest()
            ->get(static::URL_PARAM_ID_PRODUCT);

        $viewData['merchants'] = [];
        $merchantCollectionTransfer = $this->getFactory()
            ->getMerchantFacade()
            ->get((new MerchantCriteriaTransfer()));

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $viewData['merchants'][$merchantTransfer->getIdMerchant()] = $merchantTransfer;
        }

        return $viewData;
    }
}
