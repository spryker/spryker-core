<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerEngine\Zed\FlashMessenger\Business\Model;;

interface MessageTrayInterface
{
    const FLASH_MESSAGES_SUCCESS = 'flash.messages.success';
    const FLASH_MESSAGES_ERROR= 'flash.messages.error';
    const FLASH_MESSAGES_INFO = 'flash.messages.info';

    /**
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage($message);

    /**
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage($message);

    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message);
}
