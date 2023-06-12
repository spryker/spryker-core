<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business\TimeStamp;

use DateTime;
use DateTimeZone;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;

class AssetTimeStamp implements AssetTimeStampInterface
{
    /**
     * @var string
     */
    public const TIMESTAMP_FORMAT = "Y-m-d\TH:i:s.u";

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return bool
     */
    public function shouldTransferMessageBeProcessed(AssetTransfer $assetTransfer, MessageAttributesTransfer $messageAttributesTransfer): bool
    {
        if ($assetTransfer->getLastMessageTimestamp() === null) {
            return true;
        }

        return new DateTime($assetTransfer->getLastMessageTimestampOrFail()) < new DateTime($messageAttributesTransfer->getTimestampOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function updateMessageAttributesTimestampIfRequired(MessageAttributesTransfer $messageAttributesTransfer): MessageAttributesTransfer
    {
        if ($messageAttributesTransfer->getTimestamp() == null) {
            trigger_error('The MessageAttributes.Timestamp field is empty and will default to current time. This field will be required in a future version.', E_USER_DEPRECATED);
            $messageAttributesTransfer->setTimestamp((new DateTime('now', new DateTimeZone('UTC')))->format(static::TIMESTAMP_FORMAT));
        }

        return $messageAttributesTransfer;
    }
}
