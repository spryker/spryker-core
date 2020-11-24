<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Business\Reader;

use Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\MerchantShipment\Dependency\Facade\MerchantShipmentToShipmentFacadeInterface;
use Spryker\Zed\MerchantShipment\Persistence\MerchantShipmentRepositoryInterface;

class MerchantShipmentReader implements MerchantShipmentReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantShipment\Persistence\MerchantShipmentRepositoryInterface
     */
    protected $merchantShipmentRepository;

    /**
     * @var \Spryker\Zed\MerchantShipment\Dependency\Facade\MerchantShipmentToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\MerchantShipment\Persistence\MerchantShipmentRepositoryInterface $merchantShipmentRepository
     * @param \Spryker\Zed\MerchantShipment\Dependency\Facade\MerchantShipmentToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(
        MerchantShipmentRepositoryInterface $merchantShipmentRepository,
        MerchantShipmentToShipmentFacadeInterface $shipmentFacade
    ) {
        $this->merchantShipmentRepository = $merchantShipmentRepository;
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer $merchantShipmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipment(MerchantShipmentCriteriaTransfer $merchantShipmentCriteriaTransfer): ?ShipmentTransfer
    {
        $shipmentTransfer = $this->merchantShipmentRepository->findShipment($merchantShipmentCriteriaTransfer);

        if (!$shipmentTransfer) {
            return null;
        }

        $shipmentMethodTransfer = $this->getShipmentMethodTransfer($shipmentTransfer);
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        return $shipmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function getShipmentMethodTransfer(ShipmentTransfer $shipmentTransfer): ?ShipmentMethodTransfer
    {
        $shipmentMethodTransfer = $shipmentTransfer->getMethod();

        if (!$shipmentMethodTransfer) {
            return null;
        }

        $shipmentMethodName = $shipmentMethodTransfer->getName();
        if (!$shipmentMethodName) {
            return $shipmentMethodTransfer;
        }

        return $this->shipmentFacade->findShipmentMethodByName($shipmentMethodName);
    }
}
