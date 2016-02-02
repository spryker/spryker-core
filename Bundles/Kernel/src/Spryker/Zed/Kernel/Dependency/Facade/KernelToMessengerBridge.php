<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Dependency\Facade;

use Spryker\Zed\Messenger\Business\MessengerFacade;

class KernelToMessengerBridge implements KernelToMessengerInterface
{

    /**
     * @var MessengerFacade
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
     * @return \Generated\Shared\Transfer\FlashMessagesTransfer
     */
    public function getStoredMessages()
    {
        return $this->messengerFacade->getStoredMessages();
    }

}
