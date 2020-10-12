<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Business\Reader;

use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\MerchantShipment\Persistence\MerchantShipmentRepositoryInterface;

class MerchantShipmentReader implements MerchantShipmentReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantShipment\Persistence\MerchantShipmentRepositoryInterface
     */
    protected $merchantShipmentRepository;

    /**
     * @param \Spryker\Zed\MerchantShipment\Persistence\MerchantShipmentRepositoryInterface $merchantShipmentRepository
     */
    public function __construct(MerchantShipmentRepositoryInterface $merchantShipmentRepository)
    {
        $this->merchantShipmentRepository = $merchantShipmentRepository;
    }

    /**
     * @param string $merchantReference
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return bool
     */
    public function isMerchantOrderShipment(
        string $merchantReference,
        ShipmentTransfer $shipmentTransfer
    ): bool {
        $merchantShipmentCollection = $this->merchantShipmentRepository
            ->getMerchantShipments($merchantReference);

        foreach ($merchantShipmentCollection->getMerchantShipments() as $merchantShipmentTransfer) {
            if ($merchantShipmentTransfer->getIdSalesShipment() === $shipmentTransfer->getIdSalesShipment()) {
                return true;
            }
        }

        return false;
    }
}
