<?php

namespace Spryker\Zed\CartPermissionConnector\Dependency\Facade;


use Generated\Shared\Transfer\MessageTransfer;

interface CartPermissionConnectorToMessengerFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $message);
}