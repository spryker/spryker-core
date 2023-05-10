<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer;
use Spryker\Zed\ServicePoint\Business\Extractor\ErrorExtractorInterface;

class ServicePointServiceFilter implements ServicePointServiceFilterInterface
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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $validServicePointServiceTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $invalidServicePointServiceTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer>
     */
    public function mergeServicePointServices(
        ArrayObject $validServicePointServiceTransfers,
        ArrayObject $invalidServicePointServiceTransfers
    ): ArrayObject {
        foreach ($invalidServicePointServiceTransfers as $entityIdentifier => $invalidServicePointServiceTransfer) {
            $validServicePointServiceTransfers->offsetSet($entityIdentifier, $invalidServicePointServiceTransfer);
        }

        return $validServicePointServiceTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer $servicePointServiceCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer>>
     */
    public function filterServicePointServicesByValidity(
        ServicePointServiceCollectionResponseTransfer $servicePointServiceCollectionResponseTransfer
    ): array {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $servicePointServiceCollectionResponseTransfer->getErrors();
        $erroredEntityIdentifiers = $this->errorExtractor->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        $validServicePointServiceTransfers = new ArrayObject();
        $invalidServicePointServiceTransfers = new ArrayObject();

        foreach ($servicePointServiceCollectionResponseTransfer->getServicePointServices() as $entityIdentifier => $servicePointServiceTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $invalidServicePointServiceTransfers->offsetSet($entityIdentifier, $servicePointServiceTransfer);

                continue;
            }

            $validServicePointServiceTransfers->offsetSet($entityIdentifier, $servicePointServiceTransfer);
        }

        return [$validServicePointServiceTransfers, $invalidServicePointServiceTransfers];
    }
}
