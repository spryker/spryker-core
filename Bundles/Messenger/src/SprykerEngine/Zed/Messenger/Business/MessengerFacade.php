<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Messenger\Business;

use Generated\Shared\Transfer\FlashMessagesTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method MessengerDependencyContainer getDependencyContainer()
 */
class MessengerFacade extends AbstractFacade
{

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addSuccessMessage(MessageTransfer $message)
    {
        $this->getDependencyContainer()->createMessageTray()->addSuccessMessage($message);
    }

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $message)
    {
        $this->getDependencyContainer()->createMessageTray()->addErrorMessage($message);
    }

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addInfoMessage(MessageTransfer $message)
    {
        $this->getDependencyContainer()->createMessageTray()->addInfoMessage($message);
    }

    /**
     * @return FlashMessagesTransfer
     */
    public function getStoredMessages()
    {
        return $this->getDependencyContainer()->createMessageTray()->getMessages();
    }

}
