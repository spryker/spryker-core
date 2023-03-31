<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Communication\Plugin\Publisher\LocaleStore;

use Spryker\Shared\StoreStorage\StoreStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\StoreStorage\Business\StoreStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\StoreStorage\StoreStorageConfig getConfig()
 * @method \Spryker\Zed\StoreStorage\Communication\StoreStorageCommunicationFactory getFactory()
 */
class LocaleStoreWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Gets Store foreign keys from event transfers.
     * - Publishes store data to storage table.
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
        $this->getFacade()->writeCollectionByLocaleStoreEvents($transfers);
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
            StoreStorageConfig::ENTITY_SPY_LOCALE_STORE_CREATE,
            StoreStorageConfig::ENTITY_SPY_LOCALE_STORE_DELETE,
        ];
    }
}
