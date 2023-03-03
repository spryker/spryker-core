<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Extractor;

use ArrayObject;

class ErrorMessageExtractor implements ErrorMessageExtractorInterface
{
    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return array<string>
     */
    public function extractErrorMessages(ArrayObject $errorTransfers): array
    {
        $errorMessages = [];
        foreach ($errorTransfers as $errorTransfer) {
            $errorMessages[] = $errorTransfer->getMessageOrFail();
        }

        return $errorMessages;
    }
}
