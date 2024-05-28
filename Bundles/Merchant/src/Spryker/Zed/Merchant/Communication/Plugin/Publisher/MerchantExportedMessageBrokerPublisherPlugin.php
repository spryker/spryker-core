<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\MerchantExportedTransfer;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;

/**
 * @deprecated Will be removed without replacement.
 *
 * @method \Spryker\Zed\Merchant\Business\MerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 * @method \Spryker\Zed\Merchant\Communication\MerchantCommunicationFactory getFactory()
 */
class MerchantExportedMessageBrokerPublisherPlugin extends AbstractMerchantMessageBrokerPublisherPlugin
{
    /**
     * {@inheritDoc}
     * - Emits publish Merchant event to message broker.
     * - Event contains Merchant IDs.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $merchantPublisherConfigTransfer = $this->getMerchantPublisherConfigTransfer(
            $eventEntityTransfers,
            MerchantExportedTransfer::class,
        );

        $this->getFacade()->emitPublishMerchantToMessageBroker($merchantPublisherConfigTransfer);
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
        if (!$this->getConfig()->isPublishingToMessageBrokerEnabled()) {
            return [];
        }

        return [
            MerchantEvents::MERCHANT_EXPORTED,
        ];
    }
}
