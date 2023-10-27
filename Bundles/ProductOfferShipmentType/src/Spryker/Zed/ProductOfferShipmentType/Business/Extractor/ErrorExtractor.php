<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Extractor;

use ArrayObject;

class ErrorExtractor implements ErrorExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return array<string, string>
     */
    public function extractEntityIdentifiersFromErrorTransfers(ArrayObject $errorTransfers): array
    {
        $entityIdentifiers = [];

        foreach ($errorTransfers as $errorTransfer) {
            $entityIdentifiers[$errorTransfer->getEntityIdentifierOrFail()] = $errorTransfer->getEntityIdentifierOrFail();
        }

        return $entityIdentifiers;
    }
}
