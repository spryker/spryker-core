<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Grouper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;
use Spryker\Zed\MerchantCommission\Business\Extractor\ErrorExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface;

class MerchantCommissionGrouper implements MerchantCommissionGrouperInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface
     */
    protected MerchantCommissionRepositoryInterface $merchantCommissionRepository;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionDataExtractorInterface
     */
    protected MerchantCommissionDataExtractorInterface $merchantCommissionDataExtractor;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\ErrorExtractorInterface
     */
    protected ErrorExtractorInterface $errorExtractor;

    /**
     * @param \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface $merchantCommissionRepository
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionDataExtractorInterface $merchantCommissionDataExtractor
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\ErrorExtractorInterface $errorExtractor
     */
    public function __construct(
        MerchantCommissionRepositoryInterface $merchantCommissionRepository,
        MerchantCommissionDataExtractorInterface $merchantCommissionDataExtractor,
        ErrorExtractorInterface $errorExtractor
    ) {
        $this->merchantCommissionRepository = $merchantCommissionRepository;
        $this->merchantCommissionDataExtractor = $merchantCommissionDataExtractor;
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
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return list<\ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer>>
     */
    public function groupMerchantCommissionsByPersistenceExistence(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): array {
        $requestedMerchantCommissionKeys = $this->merchantCommissionDataExtractor->extractMerchantCommissionKeys(
            $merchantCommissionCollectionRequestTransfer->getMerchantCommissions(),
        );
        $persistedMerchantCommissionKeys = $this->merchantCommissionRepository->getExistingMerchantCommissionKeys(
            $requestedMerchantCommissionKeys,
        );
        $persistedMerchantCommissionKeys = array_combine($persistedMerchantCommissionKeys, $persistedMerchantCommissionKeys);

        $existingMerchantCommissions = new ArrayObject();
        $newMerchantCommissions = new ArrayObject();
        foreach ($merchantCommissionCollectionRequestTransfer->getMerchantCommissions() as $entityIdentifier => $merchantCommissionTransfer) {
            if (isset($persistedMerchantCommissionKeys[$merchantCommissionTransfer->getKeyOrFail()])) {
                $existingMerchantCommissions->offsetSet($entityIdentifier, $merchantCommissionTransfer);

                continue;
            }

            $newMerchantCommissions->offsetSet($entityIdentifier, $merchantCommissionTransfer);
        }

        return [$newMerchantCommissions, $existingMerchantCommissions];
    }

    /**
     * @param list<\Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return array<string, list<\Generated\Shared\Transfer\MerchantCommissionTransfer>>
     */
    public function getMerchantCommissionsGroupedByMerchantCommissionGroupKey(array $merchantCommissionTransfers): array
    {
        $groupedMerchantCommissions = [];
        foreach ($merchantCommissionTransfers as $merchantCommissionTransfer) {
            $merchantCommissionGroupKey = $merchantCommissionTransfer->getMerchantCommissionGroupOrFail()->getKeyOrFail();
            $groupedMerchantCommissions[$merchantCommissionGroupKey][] = $merchantCommissionTransfer;
        }

        return $groupedMerchantCommissions;
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
