<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Dependency\Facade\ProductOfferShipmentTypeMerchantPortalGuiToShipmentTypeFacadeInterface;

class ShipmentTypeProductOfferDataProvider implements ShipmentTypeProductOfferDataProviderInterface
{
    /**
     * @var string
     */
    protected const PATTERN_SHIPMENT_TYPE_CHOICE_NAME = '%s - %s';

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Dependency\Facade\ProductOfferShipmentTypeMerchantPortalGuiToShipmentTypeFacadeInterface
     */
    protected ProductOfferShipmentTypeMerchantPortalGuiToShipmentTypeFacadeInterface $shipmentTypeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Dependency\Facade\ProductOfferShipmentTypeMerchantPortalGuiToShipmentTypeFacadeInterface $shipmentTypeFacade
     */
    public function __construct(ProductOfferShipmentTypeMerchantPortalGuiToShipmentTypeFacadeInterface $shipmentTypeFacade)
    {
        $this->shipmentTypeFacade = $shipmentTypeFacade;
    }

    /**
     * @return array<string, string>
     */
    public function getShipmentTypeChoices(): array
    {
        $shipmentTypeCollectionTransfer = $this->getShipmentTypeCollection();

        $shipmentTypeChoices = [];
        foreach ($shipmentTypeCollectionTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            $shipmentTypeChoices[$this->getShipmentTypeChoiceName($shipmentTypeTransfer)] = $shipmentTypeTransfer->getUuidOrFail();
        }

        return $shipmentTypeChoices;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    protected function getShipmentTypeCollection(): ShipmentTypeCollectionTransfer
    {
        $shipmentTypeCriteriaTransfer = new ShipmentTypeCriteriaTransfer();

        return $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return string
     */
    protected function getShipmentTypeChoiceName(ShipmentTypeTransfer $shipmentTypeTransfer): string
    {
        return sprintf(
            static::PATTERN_SHIPMENT_TYPE_CHOICE_NAME,
            $shipmentTypeTransfer->getKeyOrFail(),
            $shipmentTypeTransfer->getNameOrFail(),
        );
    }
}
