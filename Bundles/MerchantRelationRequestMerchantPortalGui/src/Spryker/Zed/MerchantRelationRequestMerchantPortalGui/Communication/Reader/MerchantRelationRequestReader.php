<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Reader;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig;

class MerchantRelationRequestReader implements MerchantRelationRequestReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig
     */
    protected MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig;

    /**
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig
     */
    public function __construct(
        MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade,
        MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig
    ) {
        $this->merchantRelationRequestFacade = $merchantRelationRequestFacade;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->merchantRelationRequestMerchantPortalGuiConfig = $merchantRelationRequestMerchantPortalGuiConfig;
    }

    /**
     * @param int $idMerchantRelationRequest
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer|null
     */
    public function findCurrentMerchantUserMerchantRelationRequestByIdMerchantRelationRequest(
        int $idMerchantRelationRequest
    ): ?MerchantRelationRequestTransfer {
        $merchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions(
                (new MerchantRelationRequestConditionsTransfer())
                    ->addIdMerchant($merchantUserTransfer->getIdMerchantOrFail())
                    ->addIdMerchantRelationRequest($idMerchantRelationRequest)
                    ->setWithAssigneeCompanyBusinessUnitRelations(true)
                    ->setWithMerchantRelationshipRelations(true),
            );

        $merchantRelationRequestTransfers = $this->merchantRelationRequestFacade
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer)
            ->getMerchantRelationRequests();

        return $merchantRelationRequestTransfers->offsetExists(0)
            ? $merchantRelationRequestTransfers->offsetGet(0)
            : null;
    }

    /**
     * @return array<int, string>
     */
    public function getInCompanyIdsFilterOptions(): array
    {
        $companyFilterOptions = [];
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchantOrFail());
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer);
        $readCollectionBatchSize = $this->merchantRelationRequestMerchantPortalGuiConfig
            ->getReadMerchantRelationRequestCollectionBatchSize();
        $offset = 0;

        do {
            $paginationTransfer = (new PaginationTransfer())->setOffset($offset)->setLimit($readCollectionBatchSize);
            $merchantRelationRequestCriteriaTransfer->setPagination($paginationTransfer);

            $merchantRelationRequestCollectionTransfer = $this->merchantRelationRequestFacade
                ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

            if (!count($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests())) {
                break;
            }

            $companyFilterOptions = $this->addInCompanyIdsFilterOptions($companyFilterOptions, $merchantRelationRequestCollectionTransfer);

            $offset += $readCollectionBatchSize;
        } while (
            count($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()) !== 0
        );

        return $companyFilterOptions;
    }

    /**
     * @param array<int, string> $companyFilterOptions
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return array<int, string>
     */
    protected function addInCompanyIdsFilterOptions(
        array $companyFilterOptions,
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): array {
        foreach ($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests() as $merchantRelationRequestTransfer) {
            $companyTransfer = $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()->getCompanyOrFail();

            $companyFilterOptions[$companyTransfer->getIdCompanyOrFail()] = $companyTransfer->getNameOrFail();
        }

        return $companyFilterOptions;
    }
}
