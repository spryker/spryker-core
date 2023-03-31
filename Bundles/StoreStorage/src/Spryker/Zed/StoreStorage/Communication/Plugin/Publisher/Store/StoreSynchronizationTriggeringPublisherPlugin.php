<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Communication\Plugin\Publisher\Store;

use Spryker\Shared\StoreStorage\StoreStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\StoreStorage\Business\StoreStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\StoreStorage\StoreStorageConfig getConfig()
 * @method \Spryker\Zed\StoreStorage\Communication\StoreStorageCommunicationFactory getFactory()
 */
class StoreSynchronizationTriggeringPublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Triggers exporting synchronized data into queues for specified store resources.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        if (!$this->getConfig()->getStoreCreationResourcesToReSync()) {
            return;
        }

        $this->getFactory()->getSynchronizationFacade()
            ->executeResolvedPluginsBySourcesWithIds(
                $this->getConfig()->getStoreCreationResourcesToReSync(),
                [],
            );
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
            StoreStorageConfig::ENTITY_SPY_STORE_CREATE,
        ];
    }
}
