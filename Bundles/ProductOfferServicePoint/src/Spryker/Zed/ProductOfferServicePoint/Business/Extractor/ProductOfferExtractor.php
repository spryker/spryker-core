<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Extractor;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferTransfer;

class ProductOfferExtractor implements ProductOfferExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return list<string>
     */
    public function extractServicePointUuidsFromProductOfferTransfer(ProductOfferTransfer $productOfferTransfer): array
    {
        $servicePointUuids = [];

        foreach ($productOfferTransfer->getServices() as $serviceTransfer) {
            $servicePointUuids[] = $serviceTransfer->getServicePointOrFail()->getUuidOrFail();
        }

        return $servicePointUuids;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return list<string>
     */
    public function extractProductOfferReferencesFromProductOfferTransfers(ArrayObject $productOfferTransfers): array
    {
        $productOfferReferences = [];

        foreach ($productOfferTransfers as $productOfferTransfer) {
            $productOfferReferences[] = $productOfferTransfer->getProductOfferReferenceOrFail();
        }

        return $productOfferReferences;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return list<string>
     */
    public function extractServiceUuidsFromProductOfferTransfers(ArrayObject $productOfferTransfers): array
    {
        $serviceUuids = [];

        foreach ($productOfferTransfers as $productOfferTransfer) {
            foreach ($productOfferTransfer->getServices() as $serviceTransfer) {
                $serviceUuids[] = $serviceTransfer->getUuidOrFail();
            }
        }

        return $serviceUuids;
    }
}
