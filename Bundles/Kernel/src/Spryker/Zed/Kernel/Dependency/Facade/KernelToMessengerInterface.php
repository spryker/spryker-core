<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Dependency\Facade;

use Generated\Shared\Transfer\FlashMessagesTransfer;

interface KernelToMessengerInterface
{

    /**
     * @return FlashMessagesTransfer
     */
    public function getStoredMessages();

}
