<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer;
use Spryker\Zed\ServicePoint\Business\Extractor\ErrorExtractorInterface;

class ServicePointAddressFilter implements ServicePointAddressFilterInterface
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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer> $validServicePointAddressTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer> $invalidServicePointAddressTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer>
     */
    public function mergeServicePointAddresses(
        ArrayObject $validServicePointAddressTransfers,
        ArrayObject $invalidServicePointAddressTransfers
    ): ArrayObject {
        foreach ($invalidServicePointAddressTransfers as $entityIdentifier => $invalidServicePointAddressTransfer) {
            $validServicePointAddressTransfers->offsetSet($entityIdentifier, $invalidServicePointAddressTransfer);
        }

        return $validServicePointAddressTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer $servicePointAddressCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer>>
     */
    public function filterServicePointAddressesByValidity(
        ServicePointAddressCollectionResponseTransfer $servicePointAddressCollectionResponseTransfer
    ): array {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $servicePointAddressCollectionResponseTransfer->getErrors();
        $erroredEntityIdentifiers = $this->errorExtractor->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        $validServicePointAddressTransfers = new ArrayObject();
        $invalidServicePointAddressTransfers = new ArrayObject();

        foreach ($servicePointAddressCollectionResponseTransfer->getServicePointAddresses() as $entityIdentifier => $servicePointAddressTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $invalidServicePointAddressTransfers->offsetSet($entityIdentifier, $servicePointAddressTransfer);

                continue;
            }

            $validServicePointAddressTransfers->offsetSet($entityIdentifier, $servicePointAddressTransfer);
        }

        return [$validServicePointAddressTransfers, $invalidServicePointAddressTransfers];
    }
}
