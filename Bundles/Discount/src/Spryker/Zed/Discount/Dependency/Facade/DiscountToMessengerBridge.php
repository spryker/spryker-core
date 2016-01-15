<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Dependency\Facade;

use Spryker\Zed\Messenger\Business\MessengerFacade;
use Generated\Shared\Transfer\MessageTransfer;

class DiscountToMessengerBridge implements DiscountToMessengerInterface
{

    /**
     * @var MessengerFacade
     */
    protected $messengerFacade;

    /**
     * @param MessengerFacade $messengerFacade
     */
    public function __construct($messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addSuccessMessage(MessageTransfer $message)
    {
        $this->messengerFacade->addSuccessMessage($message);
    }

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $message)
    {
        $this->messengerFacade->addErrorMessage($message);
    }

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addInfoMessage(MessageTransfer $message)
    {
        $this->messengerFacade->addInfoMessage($message);
    }

}
