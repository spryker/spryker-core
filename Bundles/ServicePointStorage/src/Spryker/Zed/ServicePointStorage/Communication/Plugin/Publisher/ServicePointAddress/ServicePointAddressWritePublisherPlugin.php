<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Communication\Plugin\Publisher\ServicePointAddress;

use Spryker\Shared\ServicePointStorage\ServicePointStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ServicePointStorage\Business\ServicePointStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ServicePointStorage\Communication\ServicePointStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ServicePointStorage\ServicePointStorageConfig getConfig()
 */
class ServicePointAddressWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Publishes service point data by `SpyServicePointAddress` entity events.
     * - Extracts service point IDs from the `$eventEntityTransfers` created by service point address entity events.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeServicePointStorageCollectionByServicePointAddressEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            ServicePointStorageConfig::ENTITY_SPY_SERVICE_POINT_ADDRESS_CREATE,
            ServicePointStorageConfig::ENTITY_SPY_SERVICE_POINT_ADDRESS_UPDATE,
        ];
    }
}
