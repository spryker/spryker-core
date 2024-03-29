<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Communication\Plugin\Publisher\MerchantCategory;

use Spryker\Shared\MerchantSearch\MerchantSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\MerchantSearch\MerchantSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantSearch\Business\MerchantSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSearch\Communication\MerchantSearchCommunicationFactory getFactory()
 */
class MerchantCategoryWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves all Merchants using foreign keys from $eventTransfers.
     * - Updates entities from `spy_merchant_search` with actual data from obtained Merchants.
     * - Sends a copy of data to queue based on module config.
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
        $this->getFacade()->writeCollectionByMerchantCategoryEvents($eventEntityTransfers);
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
            MerchantSearchConfig::MERCHANT_CATEGORY_PUBLISH,
            MerchantSearchConfig::ENTITY_SPY_MERCHANT_CATEGORY_UPDATE,
            MerchantSearchConfig::ENTITY_SPY_MERCHANT_CATEGORY_CREATE,
            MerchantSearchConfig::ENTITY_SPY_MERCHANT_CATEGORY_DELETE,
        ];
    }
}
