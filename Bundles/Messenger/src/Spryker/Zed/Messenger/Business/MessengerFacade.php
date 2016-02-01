<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Messenger\Business;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method MessengerBusinessFactory getFactory()
 */
class MessengerFacade extends AbstractFacade
{

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addSuccessMessage(MessageTransfer $message)
    {
        $this->getFactory()->createMessageTray()->addSuccessMessage($message);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $message)
    {
        $this->getFactory()->createMessageTray()->addErrorMessage($message);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addInfoMessage(MessageTransfer $message)
    {
        $this->getFactory()->createMessageTray()->addInfoMessage($message);
    }

    /**
     * @return \Generated\Shared\Transfer\FlashMessagesTransfer
     */
    public function getStoredMessages()
    {
        return $this->getFactory()->createMessageTray()->getMessages();
    }

}
