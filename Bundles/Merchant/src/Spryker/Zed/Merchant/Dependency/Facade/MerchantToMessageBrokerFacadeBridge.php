<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Dependency\Facade;

use Generated\Shared\Transfer\MessageResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * @deprecated Will be removed without replacement.
 */
class MerchantToMessageBrokerFacadeBridge implements MerchantToMessageBrokerFacadeInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface
     */
    protected $messageBrokerFacade;

    /**
     * @param \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface $messageBrokerFacade
     */
    public function __construct($messageBrokerFacade)
    {
        $this->messageBrokerFacade = $messageBrokerFacade;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageResponseTransfer
     */
    public function sendMessage(TransferInterface $messageTransfer): MessageResponseTransfer
    {
        return $this->messageBrokerFacade->sendMessage($messageTransfer);
    }
}
