<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Dependency\Facade;

use Generated\Shared\Transfer\MessageTransfer;

interface DiscountToMessengerInterface
{

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addSuccessMessage(MessageTransfer $message);

}
