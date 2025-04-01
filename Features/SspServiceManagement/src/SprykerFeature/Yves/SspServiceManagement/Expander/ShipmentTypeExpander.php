<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Client\Store\StoreClientInterface;
use SprykerFeature\Yves\SspServiceManagement\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Yves\SspServiceManagement\Widget\ShipmentTypeServicePointSelectorWidget;

class ShipmentTypeExpander implements ShipmentTypeExpanderInterface
{
 /**
  * @param \SprykerFeature\Yves\SspServiceManagement\Reader\ShipmentTypeReaderInterface $shipmentTypeReader
  * @param \Spryker\Client\Store\StoreClientInterface $storeClient
  */
    public function __construct(
        protected ShipmentTypeReaderInterface $shipmentTypeReader,
        protected StoreClientInterface $storeClient
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithShipmentType(ItemTransfer $itemTransfer, array $params): ItemTransfer
    {
        if (!isset($params[ShipmentTypeServicePointSelectorWidget::DEFAULT_FORM_FIELD_SHIPMENT_TYPE_UUID])) {
            return $itemTransfer;
        }

        $shipmentTypeUuid = $params[ShipmentTypeServicePointSelectorWidget::DEFAULT_FORM_FIELD_SHIPMENT_TYPE_UUID] ?: null;

        if (!$shipmentTypeUuid) {
            return $itemTransfer;
        }

        $shipmentTypeStorageCollection = $this->shipmentTypeReader->getShipmentTypeStorageCollection(
            [$shipmentTypeUuid],
            $this->storeClient->getCurrentStore()->getNameOrFail(),
        );

        if (!$shipmentTypeStorageCollection->getShipmentTypeStorages()->count()) {
            return $itemTransfer;
        }

        $shipmentTypeStorage = $shipmentTypeStorageCollection->getShipmentTypeStorages()->getIterator()->current();
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())->fromArray($shipmentTypeStorage->toArray(), true);

        return $itemTransfer
            ->setShipmentType($shipmentTypeTransfer);
    }
}
