<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Communication\Plugin\Publisher\Merchant;

use Spryker\Shared\MerchantSearch\MerchantSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\MerchantSearch\MerchantSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantSearch\Business\MerchantSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSearch\Communication\MerchantSearchCommunicationFactory getFactory()
 */
class MerchantDeletePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Deletes entities from `spy_merchant_search` based on IDs from $eventTransfers.
     * - Sends delete message to queue based on module config.
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
        $this->getFacade()->deleteCollectionByMerchantEvents($eventEntityTransfers);
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
            MerchantSearchConfig::MERCHANT_PUBLISH,
            MerchantSearchConfig::ENTITY_SPY_MERCHANT_DELETE,
        ];
    }
}
