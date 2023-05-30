<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ServiceCollectionResponseTransfer;
use Spryker\Zed\ServicePoint\Business\Extractor\ErrorExtractorInterface;

class ServiceFilter implements ServiceFilterInterface
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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $validServiceTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $invalidServiceTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer>
     */
    public function mergeServices(
        ArrayObject $validServiceTransfers,
        ArrayObject $invalidServiceTransfers
    ): ArrayObject {
        foreach ($invalidServiceTransfers as $entityIdentifier => $invalidServiceTransfer) {
            $validServiceTransfers->offsetSet($entityIdentifier, $invalidServiceTransfer);
        }

        return $validServiceTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionResponseTransfer $serviceCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer>>
     */
    public function filterServicesByValidity(
        ServiceCollectionResponseTransfer $serviceCollectionResponseTransfer
    ): array {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $serviceCollectionResponseTransfer->getErrors();
        $erroredEntityIdentifiers = $this->errorExtractor->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        $validServiceTransfers = new ArrayObject();
        $invalidServiceTransfers = new ArrayObject();

        foreach ($serviceCollectionResponseTransfer->getServices() as $entityIdentifier => $serviceTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $invalidServiceTransfers->offsetSet($entityIdentifier, $serviceTransfer);

                continue;
            }

            $validServiceTransfers->offsetSet($entityIdentifier, $serviceTransfer);
        }

        return [$validServiceTransfers, $invalidServiceTransfers];
    }
}
