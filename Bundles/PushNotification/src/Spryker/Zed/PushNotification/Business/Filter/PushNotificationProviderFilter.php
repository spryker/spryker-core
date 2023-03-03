<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
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
    public function __construct(
        ErrorEntityIdentifierExtractorInterface $errorEntityIdentifierExtractor
    ) {
        $this->errorEntityIdentifierExtractor = $errorEntityIdentifierExtractor;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    public function filterOutInvalidPushNotificationProviders(
        ArrayObject $pushNotificationProviderTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ArrayObject {
        if (!$errorCollectionTransfer->getErrors()->count()) {
            return $pushNotificationProviderTransfers;
        }

        $invalidPushNotificationProviderIdentifiers = $this->errorEntityIdentifierExtractor->extractEntityIdentifiers(
            $errorCollectionTransfer,
        );

        return $this->filterValidPushNotificationProviders(
            $pushNotificationProviderTransfers,
            $invalidPushNotificationProviderIdentifiers,
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers
     * @param array<string> $invalidPushNotificationProviderIdentifiers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    protected function filterValidPushNotificationProviders(
        ArrayObject $pushNotificationProviderTransfers,
        array $invalidPushNotificationProviderIdentifiers
    ): ArrayObject {
        $validPushNotificationProviderTransfers = new ArrayObject();
        foreach ($pushNotificationProviderTransfers as $i => $pushNotificationProviderTransfer) {
            if (in_array($i, $invalidPushNotificationProviderIdentifiers)) {
                continue;
            }
            $validPushNotificationProviderTransfers->append($pushNotificationProviderTransfer);
        }

        return $validPushNotificationProviderTransfers;
    }
}
