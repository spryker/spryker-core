<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorage\Scanner;

use Exception;
use Spryker\Client\ShipmentTypeStorage\Dependency\Client\ShipmentTypeStorageToStorageClientInterface;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageConfig;

class ShipmentTypeStorageKeyScanner implements ShipmentTypeStorageKeyScannerInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_SCAN_KEY_PATTERN = 'shipment_type*uuid*';

    /**
     * @var \Spryker\Client\ShipmentTypeStorage\Dependency\Client\ShipmentTypeStorageToStorageClientInterface
     */
    protected ShipmentTypeStorageToStorageClientInterface $storageClient;

    /**
     * @var \Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageConfig
     */
    protected ShipmentTypeStorageConfig $shipmentTypeStorageConfig;

    /**
     * @param \Spryker\Client\ShipmentTypeStorage\Dependency\Client\ShipmentTypeStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageConfig $shipmentTypeStorageConfig
     */
    public function __construct(
        ShipmentTypeStorageToStorageClientInterface $storageClient,
        ShipmentTypeStorageConfig $shipmentTypeStorageConfig
    ) {
        $this->storageClient = $storageClient;
        $this->shipmentTypeStorageConfig = $shipmentTypeStorageConfig;
    }

    /**
     * @return list<string>
     */
    public function scanShipmentTypeUuids(): array
    {
        $scanKeyStoreLimit = $this->shipmentTypeStorageConfig->getScanKeyStoreLimit();

        try {
            $keys = $this->storageClient
                ->scanKeys(static::DEFAULT_SCAN_KEY_PATTERN, $scanKeyStoreLimit)
                ->getKeys();
        } catch (Exception) {
            $keys = array_slice($this->storageClient->getKeys(static::DEFAULT_SCAN_KEY_PATTERN), 0, $scanKeyStoreLimit);
        }

        $uuids = [];
        foreach ($keys as $key) {
            $parts = explode(':', $key);
            $uuids[] = end($parts);
        }

        return $uuids;
    }
}
