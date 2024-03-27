<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\MerchantRelationRequestExtractorInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationshipReaderInterface;
use Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface;

class MerchantRelationRequestExpander implements MerchantRelationRequestExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface
     */
    protected MerchantRelationRequestRepositoryInterface $merchantRelationRequestRepository;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationshipReaderInterface
     */
    protected MerchantRelationshipReaderInterface $merchantRelationshipReader;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Extractor\MerchantRelationRequestExtractorInterface
     */
    protected MerchantRelationRequestExtractorInterface $merchantRelationRequestExtractor;

    /**
     * @var list<\Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestExpanderPluginInterface>
     */
    protected array $merchantRelationRequestExpanderPlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface $merchantRelationRequestRepository
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationshipReaderInterface $merchantRelationshipReader
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Extractor\MerchantRelationRequestExtractorInterface $merchantRelationRequestExtractor
     * @param list<\Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestExpanderPluginInterface> $merchantRelationRequestExpanderPlugins
     */
    public function __construct(
        MerchantRelationRequestRepositoryInterface $merchantRelationRequestRepository,
        MerchantRelationshipReaderInterface $merchantRelationshipReader,
        MerchantRelationRequestExtractorInterface $merchantRelationRequestExtractor,
        array $merchantRelationRequestExpanderPlugins
    ) {
        $this->merchantRelationRequestRepository = $merchantRelationRequestRepository;
        $this->merchantRelationshipReader = $merchantRelationshipReader;
        $this->merchantRelationRequestExtractor = $merchantRelationRequestExtractor;
        $this->merchantRelationRequestExpanderPlugins = $merchantRelationRequestExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function expandMerchantRelationRequestCollection(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer,
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer {
        $merchantRelationRequestConditionsTransfer = $merchantRelationRequestCriteriaTransfer->getMerchantRelationRequestConditions();

        if (!$merchantRelationRequestConditionsTransfer) {
            return $this->executeMerchantRelationRequestExpanderPlugins($merchantRelationRequestCollectionTransfer);
        }

        if ($merchantRelationRequestConditionsTransfer->getWithAssigneeCompanyBusinessUnitRelations()) {
            $merchantRelationRequestCollectionTransfer = $this->expandWithAssigneeCompanyBusinessUnitRelations(
                $merchantRelationRequestCollectionTransfer,
            );
        }

        if ($merchantRelationRequestConditionsTransfer->getWithMerchantRelationshipRelations()) {
            $merchantRelationRequestCollectionTransfer = $this->expandWithMerchantRelationshipRelations(
                $merchantRelationRequestCollectionTransfer,
            );
        }

        return $this->executeMerchantRelationRequestExpanderPlugins($merchantRelationRequestCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function expandWithAssigneeCompanyBusinessUnitRelations(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequests */
        $merchantRelationRequests = $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests();
        $merchantRelationRequestIds = $this->merchantRelationRequestExtractor->extractMerchantRelationRequestIds($merchantRelationRequests);

        $companyBusinessUnitTransfersGroupedByIdMerchantRelationRequest = $this->merchantRelationRequestRepository
            ->getAssigneeCompanyBusinessUnitsGroupedByIdMerchantRelationRequest($merchantRelationRequestIds);

        $merchantRelationRequests = $this->addAssigneeCompanyBusinessUnitRelationsToMerchantRelationRequestTransfers(
            $merchantRelationRequests,
            $companyBusinessUnitTransfersGroupedByIdMerchantRelationRequest,
        );

        return $merchantRelationRequestCollectionTransfer->setMerchantRelationRequests($merchantRelationRequests);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function expandWithMerchantRelationshipRelations(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequestTransfers */
        $merchantRelationRequestTransfers = $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests();
        $merchantRelationRequestUuids = $this->extractMerchantRelationRequestUuids($merchantRelationRequestTransfers);

        $merchantRelationshipsGroupedByMerchantRelationRequestUuid = $this->merchantRelationshipReader
            ->getMerchantRelationshipsGroupedByMerchantRelationshipRequestUuid($merchantRelationRequestUuids);

        $merchantRelationRequestTransfers = $this->addMerchantRelationshipsToMerchantRelationRequestTransfers(
            $merchantRelationRequestTransfers,
            $merchantRelationshipsGroupedByMerchantRelationRequestUuid,
        );

        return $merchantRelationRequestCollectionTransfer->setMerchantRelationRequests($merchantRelationRequestTransfers);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequestTransfers
     * @param array<int, list<\Generated\Shared\Transfer\CompanyBusinessUnitTransfer>> $companyBusinessUnitTransfersGroupedByIdMerchantRelationRequest
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer>
     */
    protected function addAssigneeCompanyBusinessUnitRelationsToMerchantRelationRequestTransfers(
        ArrayObject $merchantRelationRequestTransfers,
        array $companyBusinessUnitTransfersGroupedByIdMerchantRelationRequest
    ): ArrayObject {
        foreach ($merchantRelationRequestTransfers as $merchantRelationRequestTransfer) {
            $idMerchantRelationRequest = $merchantRelationRequestTransfer->getIdMerchantRelationRequestOrFail();
            $companyBusinessUnitTransfers = $companyBusinessUnitTransfersGroupedByIdMerchantRelationRequest[$idMerchantRelationRequest] ?? [];

            if (!$companyBusinessUnitTransfers) {
                continue;
            }

            $merchantRelationRequestTransfer->setAssigneeCompanyBusinessUnits(
                new ArrayObject($companyBusinessUnitTransfers),
            );
        }

        return $merchantRelationRequestTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequestTransfers
     * @param array<string, list<\Generated\Shared\Transfer\MerchantRelationshipTransfer>> $merchantRelationshipsGroupedByMerchantRelationRequestUuid
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer>
     */
    protected function addMerchantRelationshipsToMerchantRelationRequestTransfers(
        ArrayObject $merchantRelationRequestTransfers,
        array $merchantRelationshipsGroupedByMerchantRelationRequestUuid
    ): ArrayObject {
        foreach ($merchantRelationRequestTransfers as $merchantRelationRequestTransfer) {
            $merchantRelationRequestUuid = $merchantRelationRequestTransfer->getUuidOrFail();
            $merchantRelationshipTransfers = $merchantRelationshipsGroupedByMerchantRelationRequestUuid[$merchantRelationRequestUuid] ?? [];

            if (!$merchantRelationshipTransfers) {
                continue;
            }

            $merchantRelationRequestTransfer->setMerchantRelationships(new ArrayObject($merchantRelationshipTransfers));
        }

        return $merchantRelationRequestTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequests
     *
     * @return list<string>
     */
    protected function extractMerchantRelationRequestUuids(ArrayObject $merchantRelationRequests): array
    {
        $merchantRelationRequestUuids = [];
        foreach ($merchantRelationRequests as $merchantRelationRequest) {
            $merchantRelationRequestUuids[] = $merchantRelationRequest->getUuidOrFail();
        }

        return $merchantRelationRequestUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    protected function executeMerchantRelationRequestExpanderPlugins(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer {
        foreach ($this->merchantRelationRequestExpanderPlugins as $merchantRelationRequestExpanderPlugin) {
            $merchantRelationRequestCollectionTransfer = $merchantRelationRequestExpanderPlugin->expand(
                $merchantRelationRequestCollectionTransfer,
            );
        }

        return $merchantRelationRequestCollectionTransfer;
    }
}
