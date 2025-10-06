<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderOrderProviderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanySalesConnector\CompanySalesConnectorConfig getConfig()
 * @method \Spryker\Zed\CompanySalesConnector\Business\CompanySalesConnectorBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\CompanySalesConnector\Business\CompanySalesConnectorFacadeInterface getFacade()
 */
class EditCompanyOrderCartReorderOrderProviderPlugin extends AbstractPlugin implements CartReorderOrderProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `CartReorderRequestTransfer.isAmendment` is not set to `true`.
     * - Does nothing if `CartReorderRequestTransfer.companyUserTransfer` does not exist or does not have permission
     * to edit company orders to which `CartReorderRequestTransfer.orderReference` belongs to.
     * - Otherwise finds and returns order found by `CartReorderRequestTransfer.orderReference`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrder(CartReorderRequestTransfer $cartReorderRequestTransfer): ?OrderTransfer
    {
        return $this->getBusinessFactory()
            ->createEditCompanyOrderCartReorderOrderProvider()
            ->findOrder($cartReorderRequestTransfer);
    }
}
