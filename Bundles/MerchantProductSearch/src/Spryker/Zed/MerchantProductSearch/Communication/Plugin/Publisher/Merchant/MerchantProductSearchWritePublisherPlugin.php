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
 * @method \Spryker\Zed\MerchantProductSearch\Business\MerchantProductSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductSearch\MerchantProductSearchConfig getConfig()
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
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName): void
    {
        $this->getFacade()->writeCollectionByIdMerchantEvents($transfers);
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
            MerchantProductSearchConfig::MERCHANT_PUBLISH,
            MerchantProductSearchConfig::ENTITY_SPY_MERCHANT_UPDATE,
        ];
    }
}
