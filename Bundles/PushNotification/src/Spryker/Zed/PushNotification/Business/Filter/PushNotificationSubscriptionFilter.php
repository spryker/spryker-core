<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\PushNotification\Business\Extractor\ErrorEntityIdentifierExtractorInterface;

class PushNotificationSubscriptionFilter implements PushNotificationSubscriptionFilterInterface
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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    public function filterOutInvalidPushNotificationSubscriptions(
        ArrayObject $pushNotificationSubscriptionTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ArrayObject {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $errorCollectionTransfer->getErrors();

        if (!$errorTransfers->count()) {
            return $pushNotificationSubscriptionTransfers;
        }

        $invalidPushNotificationSubscriptionIdentifiers = $this->errorEntityIdentifierExtractor
            ->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        return $this->filterValidPushNotificationSubscriptions(
            $pushNotificationSubscriptionTransfers,
            $invalidPushNotificationSubscriptionIdentifiers,
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    public function filterOutValidPushNotificationSubscriptions(
        ArrayObject $pushNotificationSubscriptionTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ArrayObject {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $errorCollectionTransfer->getErrors();

        if (!$errorTransfers->count()) {
            return new ArrayObject();
        }
        $invalidPushNotificationSubscriptionIdentifiers = $this->errorEntityIdentifierExtractor
            ->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        return $this->filterInvalidPushNotificationSubscriptions(
            $pushNotificationSubscriptionTransfers,
            $invalidPushNotificationSubscriptionIdentifiers,
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     * @param array<string> $invalidPushNotificationSubscriptionIdentifiers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    protected function filterValidPushNotificationSubscriptions(
        ArrayObject $pushNotificationSubscriptionTransfers,
        array $invalidPushNotificationSubscriptionIdentifiers
    ): ArrayObject {
        $validPushNotificationSubscriptionTransfers = [];
        foreach ($pushNotificationSubscriptionTransfers as $i => $pushNotificationSubscriptionTransfer) {
            if (in_array($i, $invalidPushNotificationSubscriptionIdentifiers)) {
                continue;
            }
            $validPushNotificationSubscriptionTransfers[] = $pushNotificationSubscriptionTransfer;
        }

        return new ArrayObject($validPushNotificationSubscriptionTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     * @param array<string, string> $invalidPushNotificationSubscriptionIdentifiers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    protected function filterInvalidPushNotificationSubscriptions(
        ArrayObject $pushNotificationSubscriptionTransfers,
        array $invalidPushNotificationSubscriptionIdentifiers
    ): ArrayObject {
        $invalidPushNotificationSubscriptionTransfers = new ArrayObject();
        foreach ($pushNotificationSubscriptionTransfers as $i => $pushNotificationSubscriptionTransfer) {
            if (!in_array($i, $invalidPushNotificationSubscriptionIdentifiers)) {
                continue;
            }
            $invalidPushNotificationSubscriptionTransfers->append($pushNotificationSubscriptionTransfer);
        }

        return $invalidPushNotificationSubscriptionTransfers;
    }
}
