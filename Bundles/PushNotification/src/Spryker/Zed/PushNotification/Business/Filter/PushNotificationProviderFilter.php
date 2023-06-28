<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer;
use Spryker\Zed\PushNotification\Business\Extractor\ErrorEntityIdentifierExtractorInterface;

class PushNotificationProviderFilter implements PushNotificationProviderFilterInterface
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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $validPushNotificationProviderTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $invalidPushNotificationProviderTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    public function mergePushNotificationProviders(
        ArrayObject $validPushNotificationProviderTransfers,
        ArrayObject $invalidPushNotificationProviderTransfers
    ): ArrayObject {
        foreach ($invalidPushNotificationProviderTransfers as $entityIdentifier => $invalidPushNotificationProviderTransfer) {
            $validPushNotificationProviderTransfers->offsetSet($entityIdentifier, $invalidPushNotificationProviderTransfer);
        }

        return $validPushNotificationProviderTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer $pushNotificationProviderCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer>>
     */
    public function filterPushNotificationProvidersByValidity(
        PushNotificationProviderCollectionResponseTransfer $pushNotificationProviderCollectionResponseTransfer
    ): array {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $pushNotificationProviderCollectionResponseTransfer->getErrors();
        $erroredEntityIdentifiers = $this->errorEntityIdentifierExtractor->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        $validPushNotificationProviderTransfers = new ArrayObject();
        $invalidPushNotificationProviderTransfers = new ArrayObject();

        foreach ($pushNotificationProviderCollectionResponseTransfer->getPushNotificationProviders() as $entityIdentifier => $pushNotificationProviderTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $invalidPushNotificationProviderTransfers->offsetSet($entityIdentifier, $pushNotificationProviderTransfer);

                continue;
            }

            $validPushNotificationProviderTransfers->offsetSet($entityIdentifier, $pushNotificationProviderTransfer);
        }

        return [$validPushNotificationProviderTransfers, $invalidPushNotificationProviderTransfers];
    }
}
