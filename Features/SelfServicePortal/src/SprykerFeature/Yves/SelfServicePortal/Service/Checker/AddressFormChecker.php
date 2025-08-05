<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Checker;

use Generated\Shared\Transfer\ItemTransfer;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;

class AddressFormChecker implements AddressFormCheckerInterface
{
    /**
     * @var list<string>
     */
    protected static array $processedShipmentTypes = [];

    /**
     * @param \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig $selfServicePortalConfig
     *
     * void
     */
    public function __construct(
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
    }

    public function isApplicableForSingleAddressPerShipmentType(
        ItemTransfer $itemTransfer
    ): bool {
        if ($itemTransfer->getRelatedBundleItemIdentifier() || $itemTransfer->getBundleItemIdentifier()) {
            return false;
        }

        $shipmentTypeKey = $itemTransfer->getShipmentType()?->getKey() ?? SelfServicePortalConfig::SHIPMENT_TYPE_DELIVERY;

        if (!$shipmentTypeKey) {
            return false;
        }

        if (!$this->isApplicableShipmentType($shipmentTypeKey)) {
            return false;
        }

        $isFieldAlreadySet = $itemTransfer->getIsSingleAddressPerShipmentType();
        if ($isFieldAlreadySet) {
            $this->addProcessedShipmentType($shipmentTypeKey);

            return true;
        }

        if (static::hasSingleAddressFieldAlreadyAddedForShipmentType($shipmentTypeKey)) {
            return false;
        }

        $this->addProcessedShipmentType($shipmentTypeKey);

        return true;
    }

    public function isApplicableShipmentType(string $shipmentTypeKey): bool
    {
        return in_array(
            $shipmentTypeKey,
            $this->selfServicePortalConfig->getApplicableShipmentTypesForSingleAddressPerShipmentType(),
            true,
        );
    }

    protected static function hasSingleAddressFieldAlreadyAddedForShipmentType(string $shipmentTypeKey): bool
    {
        return in_array($shipmentTypeKey, static::$processedShipmentTypes, true);
    }

    protected static function addProcessedShipmentType(string $shipmentTypeKey): void
    {
        if (!static::hasSingleAddressFieldAlreadyAddedForShipmentType($shipmentTypeKey)) {
            static::$processedShipmentTypes[] = $shipmentTypeKey;
        }
    }
}
