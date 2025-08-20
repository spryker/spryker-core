<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Storage;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class SspAssetStorageWriter implements SspAssetStorageWriterInterface
{
    /**
     * @uses \Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetToCompanyBusinessUnitTableMap::COL_FK_SSP_ASSET
     *
     * @var string
     */
    protected const COL_FK_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT = 'spy_ssp_asset_to_company_business_unit.fk_ssp_asset';

    /**
     * @uses \Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetToSspModelTableMap::COL_FK_SSP_ASSET
     *
     * @var string
     */
    protected const COL_FK_SSP_ASSET_TO_MODEL = 'spy_ssp_asset_to_ssp_model.fk_ssp_asset';

    public function __construct(
        protected SelfServicePortalRepositoryInterface $repository,
        protected SelfServicePortalEntityManagerInterface $entityManager,
        protected EventBehaviorFacadeInterface $eventBehaviorFacade
    ) {
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeSspAssetStorageCollectionBySspAssetToCompanyBusinessUnitEvents(array $eventEntityTransfers): void
    {
        $sspAssetIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_FK_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT,
        );

        if ($sspAssetIds === []) {
            return;
        }

        $this->writeSspAssetStorageCollection($sspAssetIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeSspAssetStorageCollectionBySspAssetToModelEvents(array $eventEntityTransfers): void
    {
        $sspAssetIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_FK_SSP_ASSET_TO_MODEL,
        );

        if ($sspAssetIds === []) {
            return;
        }

        $this->writeSspAssetStorageCollection($sspAssetIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeSspAssetStorageCollectionBySspAssetEvents(array $eventEntityTransfers): void
    {
        $sspAssetIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        if ($sspAssetIds === []) {
            return;
        }

        $this->writeSspAssetStorageCollection($sspAssetIds);
    }

    /**
     * @param array<int> $sspAssetIds
     *
     * @return void
     */
    public function writeSspAssetStorageCollection(array $sspAssetIds): void
    {
        $sspAssetCriteriaTransfer = $this->createSspAssetCriteriaTransfer($sspAssetIds);
        $sspAssetCollectionTransfer = $this->repository->getSspAssetCollection($sspAssetCriteriaTransfer);

        $foundAssetIds = [];
        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $foundAssetIds[] = $sspAssetTransfer->getIdSspAsset();
            $this->entityManager->saveSspAssetStorage($sspAssetTransfer);
        }

        $notFoundAssetIds = array_diff($sspAssetIds, $foundAssetIds);

        if (!$notFoundAssetIds) {
            return;
        }

        $this->entityManager->deleteSspAssetStorageBySspAssetIds($notFoundAssetIds);
    }

    /**
     * @param list<int> $sspAssetIds
     *
     * @return \Generated\Shared\Transfer\SspAssetCriteriaTransfer
     */
    protected function createSspAssetCriteriaTransfer(array $sspAssetIds): SspAssetCriteriaTransfer
    {
        $sspAssetConditionsTransfer = (new SspAssetConditionsTransfer())
            ->setSspAssetIds($sspAssetIds);

        $includeTransfer = (new SspAssetIncludeTransfer())
            ->setWithAssignedBusinessUnits(true)
            ->setWithSspModels(true);

        return (new SspAssetCriteriaTransfer())
            ->setSspAssetConditions($sspAssetConditionsTransfer)
            ->setInclude($includeTransfer);
    }
}
