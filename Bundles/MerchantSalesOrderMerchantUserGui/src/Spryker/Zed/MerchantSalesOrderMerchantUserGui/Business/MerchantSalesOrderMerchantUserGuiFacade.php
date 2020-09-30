<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Business;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Business\MerchantSalesOrderMerchantUserGuiBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Persistence\MerchantSalesOrderMerchantUserGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Persistence\MerchantSalesOrderMerchantUserGuiEntityManagerInterface getEntityManager()
 */
class MerchantSalesOrderMerchantUserGuiFacade extends AbstractFacade implements MerchantSalesOrderMerchantUserGuiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return bool
     */
    public function isMerchantOrderShipment(
        MerchantOrderTransfer $merchantOrderTransfer,
        ShipmentTransfer $shipmentTransfer
    ): bool {
        return $this->getFactory()
            ->createMerchantSalesOrderReader()
            ->isMerchantOrderShipment($merchantOrderTransfer, $shipmentTransfer);
    }
}
