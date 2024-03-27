<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\ErrorExtractorInterface;

class MerchantRelationRequestFilter implements MerchantRelationRequestFilterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Extractor\ErrorExtractorInterface
     */
    protected ErrorExtractorInterface $errorExtractor;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Extractor\ErrorExtractorInterface $errorExtractor
     */
    public function __construct(ErrorExtractorInterface $errorExtractor)
    {
        $this->errorExtractor = $errorExtractor;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $validMerchantRelationRequestTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $invalidMerchantRelationRequestTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer>
     */
    public function mergeMerchantRelationRequests(
        ArrayObject $validMerchantRelationRequestTransfers,
        ArrayObject $invalidMerchantRelationRequestTransfers
    ): ArrayObject {
        foreach ($invalidMerchantRelationRequestTransfers as $entityIdentifier => $invalidMerchantRelationRequestTransfer) {
            $validMerchantRelationRequestTransfers->offsetSet($entityIdentifier, $invalidMerchantRelationRequestTransfer);
        }

        return $validMerchantRelationRequestTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return list<\ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer>>
     */
    public function filterMerchantRelationRequestsByValidity(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): array {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $merchantRelationRequestCollectionResponseTransfer->getErrors();
        $erroredEntityIdentifiers = $this->errorExtractor->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        $validMerchantRelationRequestTransfers = new ArrayObject();
        $invalidMerchantRelationRequestTransfers = new ArrayObject();

        foreach ($merchantRelationRequestCollectionResponseTransfer->getMerchantRelationRequests() as $entityIdentifier => $merchantRelationRequestTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $invalidMerchantRelationRequestTransfers->offsetSet($entityIdentifier, $merchantRelationRequestTransfer);

                continue;
            }

            $validMerchantRelationRequestTransfers->offsetSet($entityIdentifier, $merchantRelationRequestTransfer);
        }

        return [$validMerchantRelationRequestTransfers, $invalidMerchantRelationRequestTransfers];
    }
}
