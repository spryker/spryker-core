<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Extractor;

use ArrayObject;

class ServiceExtractor implements ServiceExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     *
     * @return list<string>
     */
    public function extractServiceUuidsFromServiceTransfers(ArrayObject $serviceTransfers): array
    {
        $serviceUuids = [];

        foreach ($serviceTransfers as $serviceTransfer) {
            $serviceUuids[] = $serviceTransfer->getUuidOrFail();
        }

        return $serviceUuids;
    }
}
