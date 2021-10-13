<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Communication\Plugin\Publisher\MerchantProductOption;

use Spryker\Shared\MerchantProductOptionStorage\MerchantProductOptionStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOptionStorage\MerchantProductOptionStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOptionStorage\Business\MerchantProductOptionStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOptionStorage\Communication\MerchantProductOptionStorageCommunicationFactory getFactory()
 */
class MerchantProductOptionGroupWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves all abstract product ids using merchant product option group ids from $eventTransfers.
     * - Runs `ProductOptionStorageFacade::publish()` with received abstract product ids.
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
        $this->getFacade()->writeCollectionByMerchantProductOptionGroupEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     * - Registers an array of events that should be handled.
     *
     * @api
     *
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            MerchantProductOptionStorageConfig::MERCHANT_PRODUCT_OPTION_GROUP_PUBLISH,
            MerchantProductOptionStorageConfig::ENTITY_SPY_MERCHANT_PRODUCT_OPTION_GROUP_CREATE,
            MerchantProductOptionStorageConfig::ENTITY_SPY_MERCHANT_PRODUCT_OPTION_GROUP_UPDATE,
        ];
    }
}
