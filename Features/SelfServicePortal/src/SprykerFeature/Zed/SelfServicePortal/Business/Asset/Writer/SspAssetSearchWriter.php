<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Generated\Shared\Transfer\SspAssetSearchCollectionTransfer;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Mapper\SspAssetSearchMapperInterface as MapperSspAssetSearchMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;

class SspAssetSearchWriter implements SspAssetSearchWriterInterface
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
        protected SspAssetReaderInterface $sspAssetReader,
        protected EventBehaviorFacadeInterface $eventBehaviorFacade,
        protected MapperSspAssetSearchMapperInterface $sspAssetSearchMapper,
        protected SelfServicePortalEntityManagerInterface $selfServicePortalEntityManager
    ) {
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionBySspAssetEvents(array $eventTransfers): void
    {
        $sspAssetIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
        $this->writeCollectionBySspAssetIds($sspAssetIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionBySspAssetToCompanyBusinessUnitEvents(array $eventEntityTransfers): void
    {
        $sspAssetIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_FK_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT,
        );

        if ($sspAssetIds === []) {
            return;
        }

        $this->writeCollectionBySspAssetIds($sspAssetIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionBySspAssetToModelEvents(array $eventEntityTransfers): void
    {
        $sspAssetIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_FK_SSP_ASSET_TO_MODEL,
        );

        if ($sspAssetIds === []) {
            return;
        }

        $this->writeCollectionBySspAssetIds($sspAssetIds);
    }

    /**
     * @param array<int> $sspAssetIds
     *
     * @return void
     */
    protected function writeCollectionBySspAssetIds(array $sspAssetIds): void
    {
        if (!$sspAssetIds) {
            return;
        }

        $sspAssetCriteriaTransfer = $this->createSspAssetCriteriaTransfer($sspAssetIds);
        $sspAssetCollectionTransfer = $this->sspAssetReader->getSspAssetCollection($sspAssetCriteriaTransfer);

        $sspAssetSearchCollectionTransfer = $this->sspAssetSearchMapper
            ->mapSspAssetCollectionTransferToSspAssetSearchCollectionTransfer(
                $sspAssetCollectionTransfer,
                new SspAssetSearchCollectionTransfer(),
            );

        $foundAssetIds = [];
        foreach ($sspAssetSearchCollectionTransfer->getSspAssets() as $sspAssetSearchTransfer) {
            $foundAssetIds[] = $sspAssetSearchTransfer->getIdSspAsset();
            $this->selfServicePortalEntityManager->saveSspAssetSearch($sspAssetSearchTransfer);
        }

        $notFoundAssetIds = array_diff($sspAssetIds, $foundAssetIds);

        if (!$notFoundAssetIds) {
            return;
        }

        $this->selfServicePortalEntityManager->deleteSspAssetSearchBySspAssetIds($notFoundAssetIds);
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
            ->setWithSspModels(true)
            ->setWithAssignedBusinessUnits(true);

        return (new SspAssetCriteriaTransfer())
            ->setSspAssetConditions($sspAssetConditionsTransfer)
            ->setInclude($includeTransfer);
    }
}
