<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\ExportMerchantsTransfer;
use Generated\Shared\Transfer\MerchantExportCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\Merchant\Business\MerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\Merchant\Communication\MerchantCommunicationFactory getFactory()
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 */
class MerchantExportMerchantsMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Triggers Merchant.exported event for all Merchants
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ExportMerchantsTransfer $exportMerchantsTransfer
     *
     * @return void
     */
    public function onExportMerchants(ExportMerchantsTransfer $exportMerchantsTransfer): void
    {
        $merchantExportCriteriaTransfer = (new MerchantExportCriteriaTransfer())->setStoreReference($exportMerchantsTransfer->getMessageAttributesOrFail()->getStoreReferenceOrFail());
        $this->getFacade()->triggerMerchantExportEvents($merchantExportCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        yield ExportMerchantsTransfer::class => [$this, 'onExportMerchants'];
    }
}
