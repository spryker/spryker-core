<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Reader;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\Expander\MerchantRelationRequestExpanderInterface;
use Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface;

class MerchantRelationRequestReader implements MerchantRelationRequestReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface
     */
    protected MerchantRelationRequestRepositoryInterface $merchantRelationRequestRepository;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Expander\MerchantRelationRequestExpanderInterface
     */
    protected MerchantRelationRequestExpanderInterface $merchantRelationRequestExpander;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface $merchantRelationRequestRepository
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Expander\MerchantRelationRequestExpanderInterface $merchantRelationRequestExpander
     */
    public function __construct(
        MerchantRelationRequestRepositoryInterface $merchantRelationRequestRepository,
        MerchantRelationRequestExpanderInterface $merchantRelationRequestExpander
    ) {
        $this->merchantRelationRequestRepository = $merchantRelationRequestRepository;
        $this->merchantRelationRequestExpander = $merchantRelationRequestExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function getMerchantRelationRequestCollection(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCollectionTransfer {
        $merchantRelationRequestCollectionTransfer = $this->merchantRelationRequestRepository
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

        if ($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->count() === 0) {
            return $merchantRelationRequestCollectionTransfer;
        }

        return $this->merchantRelationRequestExpander->expandMerchantRelationRequestCollection(
            $merchantRelationRequestCriteriaTransfer,
            $merchantRelationRequestCollectionTransfer,
        );
    }

    /**
     * @param list<string> $uuids
     *
     * @return array<string, \Generated\Shared\Transfer\MerchantRelationRequestTransfer>
     */
    public function getMerchantRelationRequestsIndexedByUuid(array $uuids): array
    {
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->setWithAssigneeCompanyBusinessUnitRelations(true)
            ->setUuids($uuids);

        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);

        $existingMerchantRelationRequests = $this
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer)
            ->getMerchantRelationRequests();

        $indexedMerchantRelationRequests = [];
        foreach ($existingMerchantRelationRequests as $existingMerchantRelationRequest) {
            $indexedMerchantRelationRequests[$existingMerchantRelationRequest->getUuidOrFail()] = $existingMerchantRelationRequest;
        }

        return $indexedMerchantRelationRequests;
    }

    /**
     * @param string $merchantRelationRequestUuid
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer|null
     */
    public function findMerchantRelationRequestByUuid(string $merchantRelationRequestUuid): ?MerchantRelationRequestTransfer
    {
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions(
                (new MerchantRelationRequestConditionsTransfer())
                    ->setWithAssigneeCompanyBusinessUnitRelations(true)
                    ->addUuid($merchantRelationRequestUuid),
            );

        return $this->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer)
            ->getMerchantRelationRequests()
            ->getIterator()
            ->current();
    }
}
