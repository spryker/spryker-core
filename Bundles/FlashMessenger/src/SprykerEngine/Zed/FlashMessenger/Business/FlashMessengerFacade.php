<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\FlashMessenger\Business;

use Generated\Shared\Transfer\FlashMessagesTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method FlashMessengerDependencyContainer getDependencyContainer()
 */
class FlashMessengerFacade extends AbstractFacade
{

    /**
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage($message)
    {
        $this->getDependencyContainer()->createMessageTray()->addSuccessMessage($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message)
    {
        $this->getDependencyContainer()->createMessageTray()->addErrorMessage($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage($message)
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
