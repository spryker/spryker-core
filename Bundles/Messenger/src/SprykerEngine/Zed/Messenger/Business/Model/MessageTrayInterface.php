<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerEngine\Zed\Messenger\Business\Model;

use Generated\Shared\Transfer\FlashMessagesTransfer;
use Generated\Shared\Transfer\MessageTransfer;

interface MessageTrayInterface
{

    const FLASH_MESSAGES_SUCCESS = 'flash.messages.success';
    const FLASH_MESSAGES_ERROR= 'flash.messages.error';
    const FLASH_MESSAGES_INFO = 'flash.messages.info';

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addSuccessMessage(MessageTransfer $message);

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addInfoMessage(MessageTransfer $message);

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $message);

    /**
     * @return FlashMessagesTransfer
     */
    public function getMessages();

}
