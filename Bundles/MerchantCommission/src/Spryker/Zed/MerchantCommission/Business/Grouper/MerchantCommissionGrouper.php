<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Grouper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;
use Spryker\Zed\MerchantCommission\Business\Extractor\ErrorExtractorInterface;

class MerchantCommissionGrouper implements MerchantCommissionGrouperInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\ErrorExtractorInterface
     */
    protected ErrorExtractorInterface $errorExtractor;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\ErrorExtractorInterface $errorExtractor
     */
    public function __construct(ErrorExtractorInterface $errorExtractor)
    {
        $this->errorExtractor = $errorExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionResponseTransfer
     *
     * @return list<\ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer>>
     */
    public function groupMerchantCommissionsByValidity(
        MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionResponseTransfer
    ): array {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $merchantCommissionCollectionResponseTransfer->getErrors();
        $erroredEntityIdentifiers = $this->errorExtractor->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        $validMerchantCommissionTransfers = new ArrayObject();
        $invalidMerchantCommissionTransfers = new ArrayObject();

        foreach ($merchantCommissionCollectionResponseTransfer->getMerchantCommissions() as $entityIdentifier => $merchantCommissionTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $invalidMerchantCommissionTransfers->offsetSet($entityIdentifier, $merchantCommissionTransfer);

                continue;
            }

            $validMerchantCommissionTransfers->offsetSet($entityIdentifier, $merchantCommissionTransfer);
        }

        return [$validMerchantCommissionTransfers, $invalidMerchantCommissionTransfers];
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $baseMerchantCommissionTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $additionalMerchantCommissionTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    public function mergeMerchantCommissionTransfers(
        ArrayObject $baseMerchantCommissionTransfers,
        ArrayObject $additionalMerchantCommissionTransfers
    ): ArrayObject {
        foreach ($additionalMerchantCommissionTransfers as $entityIdentifier => $merchantCommissionTransfer) {
            $baseMerchantCommissionTransfers->offsetSet($entityIdentifier, $merchantCommissionTransfer);
        }

        return $baseMerchantCommissionTransfers;
    }
}
