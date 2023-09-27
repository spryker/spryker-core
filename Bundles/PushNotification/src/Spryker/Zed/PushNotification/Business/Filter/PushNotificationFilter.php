<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\PushNotification\Business\Extractor\ErrorEntityIdentifierExtractorInterface;

class PushNotificationFilter implements PushNotificationFilterInterface
{
    /**
     * @var \Spryker\Zed\PushNotification\Business\Extractor\ErrorEntityIdentifierExtractorInterface
     */
    protected ErrorEntityIdentifierExtractorInterface $errorEntityIdentifierExtractor;

    /**
     * @param \Spryker\Zed\PushNotification\Business\Extractor\ErrorEntityIdentifierExtractorInterface $errorEntityIdentifierExtractor
     */
    public function __construct(ErrorEntityIdentifierExtractorInterface $errorEntityIdentifierExtractor)
    {
        $this->errorEntityIdentifierExtractor = $errorEntityIdentifierExtractor;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer>
     */
    public function filterOutInvalidPushNotifications(
        ArrayObject $pushNotificationTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ArrayObject {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $errorCollectionTransfer->getErrors();

        if (!$errorTransfers->count()) {
            return $pushNotificationTransfers;
        }

        $invalidPushNotificationIdentifiers = $this->errorEntityIdentifierExtractor
            ->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        return $this->filterValidPushNotifications(
            $pushNotificationTransfers,
            $invalidPushNotificationIdentifiers,
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer>
     */
    public function filterOutValidPushNotifications(
        ArrayObject $pushNotificationTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ArrayObject {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $errorCollectionTransfer->getErrors();

        if (!$errorTransfers->count()) {
            return new ArrayObject();
        }

        $invalidPushNotificationIdentifiers = $this->errorEntityIdentifierExtractor
            ->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        return $this->filterInvalidPushNotifications(
            $pushNotificationTransfers,
            $invalidPushNotificationIdentifiers,
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     * @param array<string, string> $invalidPushNotificationIdentifiers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer>
     */
    protected function filterValidPushNotifications(
        ArrayObject $pushNotificationTransfers,
        array $invalidPushNotificationIdentifiers
    ): ArrayObject {
        $validPushNotificationTransfers = [];
        foreach ($pushNotificationTransfers as $i => $pushNotificationTransfer) {
            if (in_array($i, $invalidPushNotificationIdentifiers)) {
                continue;
            }
            $validPushNotificationTransfers[] = $pushNotificationTransfer;
        }

        return new ArrayObject($validPushNotificationTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     * @param array<string> $invalidPushNotificationIdentifiers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer>
     */
    protected function filterInvalidPushNotifications(
        ArrayObject $pushNotificationTransfers,
        array $invalidPushNotificationIdentifiers
    ): ArrayObject {
        $invalidPushNotificationTransfers = new ArrayObject();
        foreach ($pushNotificationTransfers as $i => $pushNotificationTransfer) {
            if (!in_array($i, $invalidPushNotificationIdentifiers)) {
                continue;
            }
            $invalidPushNotificationTransfers->append($pushNotificationTransfer);
        }

        return $invalidPushNotificationTransfers;
    }
}
