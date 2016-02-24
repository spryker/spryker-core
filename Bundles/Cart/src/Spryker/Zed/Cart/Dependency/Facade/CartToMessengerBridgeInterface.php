<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Cart\Dependency\Facade;

use Generated\Shared\Transfer\MessageTransfer;

interface CartToMessengerBridgeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return void
     */
    public function addSuccessMessage(MessageTransfer $messageTransfer);

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $messageTransfer);

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return void
     */
    public function addInfoMessage(MessageTransfer $messageTransfer);
}
