<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Business;

use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantShipment\Business\MerchantShipmentBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantShipment\Persistence\MerchantShipmentRepositoryInterface getRepository()
 */
class MerchantShipmentFacade extends AbstractFacade implements MerchantShipmentFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $merchantReference
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return bool
     */
    public function isMerchantOrderShipment(
        string $merchantReference,
        ShipmentTransfer $shipmentTransfer
    ): bool {
        return $this->getFactory()
            ->createMerchantShipmentReader()
            ->isMerchantOrderShipment($merchantReference, $shipmentTransfer);
    }
}
