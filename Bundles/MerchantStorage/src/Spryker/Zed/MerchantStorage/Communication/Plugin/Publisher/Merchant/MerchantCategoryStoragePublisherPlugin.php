<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Communication\Plugin\Publisher\Merchant;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantCategory\Dependency\MerchantCategoryEvents;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\MerchantStorage\Business\MerchantStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantStorage\MerchantStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantStorage\Communication\MerchantStorageCommunicationFactory getFactory()
 */
class MerchantCategoryStoragePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Gets MerchantIds from event transfers foreign keys.
     * - Publish merchant data to storage table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeCollectionByMerchantCategoryEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            MerchantCategoryEvents::MERCHANT_CATEGORY_PUBLISH,
            MerchantCategoryEvents::ENTITY_SPY_MERCHANT_CATEGORY_UPDATE,
            MerchantCategoryEvents::ENTITY_SPY_MERCHANT_CATEGORY_CREATE,
            MerchantCategoryEvents::ENTITY_SPY_MERCHANT_CATEGORY_DELETE,
        ];
    }
}
