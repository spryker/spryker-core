<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\Publisher\Product;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Communication\MerchantProductOfferSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchConfig getConfig()
 */
class MerchantSearchPublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * - Gets MerchantIds from event transfers.
     * - Retrieves product abstract ids by MerchantIds.
     * - Publish product abstracts ids.
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
        $this->getBusinessFactory()->createProductEventTrigger()->triggerMerchantProducts($eventEntityTransfers);
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
            MerchantEvents::MERCHANT_PUBLISH,
            MerchantEvents::ENTITY_SPY_MERCHANT_UPDATE,
        ];
    }
}
