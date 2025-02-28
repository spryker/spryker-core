<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Communication\Plugin\Publisher\Store;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\TaxApp\Business\TaxAppFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxApp\TaxAppConfig getConfig()
 * @method \Spryker\Zed\TaxApp\Communication\TaxAppCommunicationFactory getFactory()
 */
class RefreshTaxAppStoreRelationPublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * @uses \Spryker\Shared\StoreStorage\StoreStorageConfig::ENTITY_SPY_STORE_CREATE
     *
     * @var string
     */
    protected const ENTITY_SPY_STORE_CREATE = 'Entity.spy_store.create';

    /**
     * {@inheritDoc}
     * - Fetches a collection of TaxApp configs from the Persistence.
     * - Iterates over the collection and triggers an update for each TaxApp config, creating non-existent store relations.
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
        $this->getFacade()->refreshTaxAppStoreRelations();
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
            static::ENTITY_SPY_STORE_CREATE,
        ];
    }
}
