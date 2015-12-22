<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Dependency\Facade;

use Generated\Shared\Transfer\FlashMessagesTransfer;

class KernelToMessengerBridge implements KernelToMessengerInterface
{

    /**
     * @var \Spryker\Zed\Messenger\Business\MessengerFacade
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\Messenger\Business\MessengerFacade $messengerFacade
     */
    public function __construct($messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @return FlashMessagesTransfer
     */
    public function getStoredMessages()
    {
        return $this->messengerFacade->getStoredMessages();
    }

}
