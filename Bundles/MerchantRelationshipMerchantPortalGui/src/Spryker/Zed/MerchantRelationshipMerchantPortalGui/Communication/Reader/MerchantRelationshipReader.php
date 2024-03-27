<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Reader;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiConfig;

class MerchantRelationshipReader implements MerchantRelationshipReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiConfig
     */
    protected MerchantRelationshipMerchantPortalGuiConfig $merchantRelationshipMerchantPortalGuiConfig;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface
     */
    protected MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiConfig $merchantRelationshipMerchantPortalGuiConfig
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        MerchantRelationshipMerchantPortalGuiConfig $merchantRelationshipMerchantPortalGuiConfig,
        MerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade,
        MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->merchantRelationshipMerchantPortalGuiConfig = $merchantRelationshipMerchantPortalGuiConfig;
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipById(int $idMerchantRelationship): ?MerchantRelationshipTransfer
    {
        $merchantRelationshipCriteriaTransfer = $this->createMerchantRelationshipCriteriaTransfer();
        $merchantRelationshipCriteriaTransfer->getMerchantRelationshipConditionsOrFail()
            ->addIdMerchantRelationship($idMerchantRelationship);

        /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
        $merchantRelationshipCollectionTransfer = $this->merchantRelationshipFacade
            ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);

        return $merchantRelationshipCollectionTransfer->getMerchantRelationships()->getIterator()->current();
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function getMerchantRelationshipCollection(): MerchantRelationshipCollectionTransfer
    {
        $merchantRelationshipCriteriaTransfer = $this->createMerchantRelationshipCriteriaTransfer();

        /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
        $merchantRelationshipCollectionTransfer = $this->merchantRelationshipFacade->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        return $merchantRelationshipCollectionTransfer;
    }

    /**
     * @return array<int, string>
     */
    public function getInCompanyIdsFilterOptions(): array
    {
        $companyFilterOptions = [];

        $merchantRelationshipCriteriaTransfer = $this->createMerchantRelationshipCriteriaTransfer();
        $readCollectionBatchSize = $this->merchantRelationshipMerchantPortalGuiConfig
            ->getReadMerchantRelationshipCollectionBatchSize();
        $offset = 0;

        do {
            $paginationTransfer = (new PaginationTransfer())
                ->setFirstIndex($offset)
                ->setMaxPerPage($readCollectionBatchSize);
            $merchantRelationshipCriteriaTransfer->setPagination($paginationTransfer);

            /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
            $merchantRelationshipCollectionTransfer = $this->merchantRelationshipFacade
                ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);

            if ($merchantRelationshipCollectionTransfer->getMerchantRelationships()->count() === 0) {
                break;
            }

            $companyFilterOptions = $this->addInCompanyIdsFilterOptions($companyFilterOptions, $merchantRelationshipCollectionTransfer);

            $offset += $readCollectionBatchSize;
        } while (
            $merchantRelationshipCollectionTransfer->getMerchantRelationships()->count() !== 0
        );

        return $companyFilterOptions;
    }

    /**
     * @param array<int, string> $companyFilterOptions
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return array<int, string>
     */
    protected function addInCompanyIdsFilterOptions(
        array $companyFilterOptions,
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): array {
        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $companyTransfer = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail()->getCompanyOrFail();

            $companyFilterOptions[$companyTransfer->getIdCompanyOrFail()] = $companyTransfer->getNameOrFail();
        }

        return $companyFilterOptions;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    protected function createMerchantRelationshipCriteriaTransfer(): MerchantRelationshipCriteriaTransfer
    {
        $merchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->addIdMerchant($merchantUserTransfer->getIdMerchantOrFail());

        return (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);
    }
}
