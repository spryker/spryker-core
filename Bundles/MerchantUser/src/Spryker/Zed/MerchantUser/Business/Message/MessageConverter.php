<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Message;

use ArrayObject;
use Generated\Shared\Transfer\MerchantErrorTransfer;

class MessageConverter implements MessageConverterInterface
{
    /**
     * @param \ArrayObject $messageTransfers
     *
     * @return \ArrayObject
     */
    public function convertMessageTransfersToMerchantErrorTransfers(ArrayObject $messageTransfers): ArrayObject
    {
        $result = new ArrayObject();
        /** @var \Generated\Shared\Transfer\MessageTransfer $messageTransfer */
        foreach ($messageTransfers as $messageTransfer) {
            $result[] = (new MerchantErrorTransfer())->setMessage($messageTransfer->getMessage());
        }

        return $result;
    }
}
