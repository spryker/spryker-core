<?php

namespace Spryker\Zed\CartPermissionConnector\Dependency\Facade;


use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Messenger\Business\MessengerFacadeInterface;

class CartPermissionConnectorToMessengerFacadeBridge implements CartPermissionConnectorToMessengerFacadeInterface
{
    /**
     * @var MessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param MessengerFacadeInterface $messengerFacade
     */
    public function __construct($messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $message)
    {
        $this->messengerFacade->addErrorMessage($message);
    }
}