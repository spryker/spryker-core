<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Extractor;

use Generated\Shared\Transfer\ErrorCollectionTransfer;

class ErrorEntityIdentifierExtractor implements ErrorEntityIdentifierExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return array<string>
     */
    public function extractEntityIdentifiers(ErrorCollectionTransfer $errorCollectionTransfer): array
    {
        $entityIdentifiers = [];
        foreach ($errorCollectionTransfer->getErrors() as $errorTransfer) {
            $entityIdentifiers[] = $errorTransfer->getEntityIdentifierOrFail();
        }

        return $entityIdentifiers;
    }
}
