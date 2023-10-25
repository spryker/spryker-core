<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Business\Writer;

interface ShipmentTypeStorageWriterInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeShipmentTypeStorageCollectionByShipmentTypeEvents(array $eventEntityTransfers): void;

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeShipmentTypeStorageCollectionByShipmentTypeStoreEvents(array $eventEntityTransfers): void;

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeShipmentTypeStorageCollectionByShipmentMethodEvents(array $eventEntityTransfers): void;

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeShipmentTypeStorageCollectionByShipmentMethodPublishEvents(array $eventEntityTransfers): void;

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeShipmentTypeStorageCollectionByShipmentMethodStoreEvents(array $eventEntityTransfers): void;

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeShipmentTypeStorageCollectionByShipmentCarrierEvents(array $eventEntityTransfers): void;
}
