<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextStorage\Communication\Plugin\Publisher;

use Spryker\Shared\StoreContextStorage\StoreContextStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\StoreContextStorage\Business\StoreContextStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\StoreContextStorage\StoreContextStorageConfig getConfig()
 */
class ContextStoreWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Gets store foreign keys from event transfers.
     * - Publishes store context data to storage table.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName): void
    {
        $this->getFacade()->writeStoreContextStorageCollectionByStoreEvents($transfers);
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
            StoreContextStorageConfig::ENTITY_SPY_STORE_CONTEXT_CREATE,
            StoreContextStorageConfig::ENTITY_SPY_STORE_CONTEXT_UPDATE,
            StoreContextStorageConfig::ENTITY_SPY_STORE_CONTEXT_DELETE,
        ];
    }
}
