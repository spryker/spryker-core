<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Communication\Plugin\Publisher\Merchant;

use Spryker\Shared\MerchantProductSearch\MerchantProductSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\MerchantProduct\Communication\Plugin\Publisher\MerchantProductPublisherPlugin} instead.
 *
 * @method \Spryker\Zed\MerchantProductSearch\Business\MerchantProductSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductSearch\MerchantProductSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantProductSearch\Communication\MerchantProductSearchCommunicationFactory getFactory()
 */
class MerchantProductSearchWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Gets MerchantIds from event transfers.
     * - Retrieves product abstract ids by MerchantIds.
     * - Publish merchant product data to Elasticsearch.
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
        $this->getFacade()->writeCollectionByIdMerchantEvents($eventEntityTransfers);
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
            MerchantProductSearchConfig::MERCHANT_PUBLISH,
            MerchantProductSearchConfig::ENTITY_SPY_MERCHANT_UPDATE,
        ];
    }
}
