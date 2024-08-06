<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\MerchantAppOnboardingStatusChangedTransfer;
use Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\MerchantApp\Business\MerchantAppFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantApp\MerchantAppConfig getConfig()
 */
class MerchantAppOnboardingMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer
     *
     * @return void
     */
    public function onReadyForMerchantAppOnboarding(ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer): void
    {
        $this->getFacade()->handleReadyForMerchantAppOnboarding($readyForMerchantAppOnboardingTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingStatusChangedTransfer $merchantOnboardingStatusChangedTransfer
     *
     * @return void
     */
    public function onMerchantAppOnboardingStatusChanged(MerchantAppOnboardingStatusChangedTransfer $merchantOnboardingStatusChangedTransfer): void
    {
        $this->getFacade()->handleMerchantAppOnboardingStatusChanged($merchantOnboardingStatusChangedTransfer);
    }

    /**
     * {@inheritDoc}
     * Return an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        yield ReadyForMerchantAppOnboardingTransfer::class => [$this, 'onReadyForMerchantAppOnboarding'];
        yield MerchantAppOnboardingStatusChangedTransfer::class => [$this, 'onMerchantAppOnboardingStatusChanged'];
    }
}
