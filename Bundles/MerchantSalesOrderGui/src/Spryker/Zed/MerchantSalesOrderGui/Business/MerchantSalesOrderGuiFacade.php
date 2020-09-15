<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Business;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\Business\MerchantSalesOrderGuiBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrderGui\Persistence\MerchantSalesOrderGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantSalesOrderGui\Persistence\MerchantSalesOrderGuiEntityManagerInterface getEntityManager()
 */
class MerchantSalesOrderGuiFacade extends AbstractFacade implements MerchantSalesOrderGuiFacadeInterface
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
