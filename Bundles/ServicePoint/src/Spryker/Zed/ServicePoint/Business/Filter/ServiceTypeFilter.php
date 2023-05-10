<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer;
use Spryker\Zed\ServicePoint\Business\Extractor\ErrorExtractorInterface;

class ServiceTypeFilter implements ServiceTypeFilterInterface
{
    /**
     * @var \Spryker\Zed\ServicePoint\Business\Extractor\ErrorExtractorInterface
     */
    protected ErrorExtractorInterface $errorExtractor;

    /**
     * @param \Spryker\Zed\ServicePoint\Business\Extractor\ErrorExtractorInterface $errorExtractor
     */
    public function __construct(ErrorExtractorInterface $errorExtractor)
    {
        $this->errorExtractor = $errorExtractor;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer> $validServiceTypeTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer> $invalidServiceTypeTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer>
     */
    public function mergeServiceTypes(
        ArrayObject $validServiceTypeTransfers,
        ArrayObject $invalidServiceTypeTransfers
    ): ArrayObject {
        foreach ($invalidServiceTypeTransfers as $entityIdentifier => $invalidServiceTypeTransfer) {
            $validServiceTypeTransfers->offsetSet($entityIdentifier, $invalidServiceTypeTransfer);
        }

        return $validServiceTypeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer $serviceTypeCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer>>
     */
    public function filterServiceTypesByValidity(
        ServiceTypeCollectionResponseTransfer $serviceTypeCollectionResponseTransfer
    ): array {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $serviceTypeCollectionResponseTransfer->getErrors();
        $erroredEntityIdentifiers = $this->errorExtractor->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        $validServiceTypeTransfers = new ArrayObject();
        $invalidServiceTypeTransfers = new ArrayObject();

        foreach ($serviceTypeCollectionResponseTransfer->getServiceTypes() as $entityIdentifier => $serviceTypeTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $invalidServiceTypeTransfers->offsetSet($entityIdentifier, $serviceTypeTransfer);

                continue;
            }

            $validServiceTypeTransfers->offsetSet($entityIdentifier, $serviceTypeTransfer);
        }

        return [$validServiceTypeTransfers, $invalidServiceTypeTransfers];
    }
}
