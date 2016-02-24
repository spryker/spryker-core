<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Dependency\Facade;

use Generated\Shared\Transfer\MessageTransfer;

class CartToMessengerBridge implements CartToMessengerBridgeInterface
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
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return void
     */
    public function addSuccessMessage(MessageTransfer $messageTransfer)
    {
        $this->messengerFacade->addSuccessMessage($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $messageTransfer)
    {
        $this->messengerFacade->addErrorMessage($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return void
     */
    public function addInfoMessage(MessageTransfer $messageTransfer)
    {
        $this->messengerFacade->addErrorMessage($messageTransfer);
    }


}
