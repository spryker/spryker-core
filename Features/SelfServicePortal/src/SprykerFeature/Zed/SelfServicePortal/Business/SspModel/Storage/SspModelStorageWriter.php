<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Storage;

use Generated\Shared\Transfer\SspModelConditionsTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class SspModelStorageWriter implements SspModelStorageWriterInterface
{
    /**
     * @uses \Orm\Zed\SelfServicePortal\Persistence\Map\SpySspModelToProductListTableMap::COL_FK_SSP_MODEL
     *
     * @var string
     */
    protected const COL_FK_SSP_MODEL = 'spy_ssp_model_to_product_list.fk_ssp_model';

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
    public function writeSspModelStorageCollectionBySspModelEvents(array $eventEntityTransfers): void
    {
        $sspModelIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        if ($sspModelIds === []) {
            return;
        }

        $this->writeSspModelStorageCollection($sspModelIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeSspModelStorageCollectionBySspModelToProductListEvents(array $eventEntityTransfers): void
    {
        $sspModelIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_FK_SSP_MODEL,
        );

        if ($sspModelIds === []) {
            return;
        }

        $this->writeSspModelStorageCollection($sspModelIds);
    }

    /**
     * @param array<int> $sspModelIds
     *
     * @return void
     */
    protected function writeSspModelStorageCollection(array $sspModelIds): void
    {
        $sspModelCriteriaTransfer = $this->createSspModelCriteriaTransfer($sspModelIds);
        $sspModelCollectionTransfer = $this->repository->getSspModelCollection($sspModelCriteriaTransfer);

        $foundModelIds = [];
        foreach ($sspModelCollectionTransfer->getSspModels() as $sspModelTransfer) {
            $foundModelIds[] = $sspModelTransfer->getIdSspModel();
            $this->entityManager->saveSspModelStorage($sspModelTransfer);
        }

        $notFoundModelIds = array_diff($sspModelIds, $foundModelIds);

        if (!$notFoundModelIds) {
            return;
        }

        $this->entityManager->deleteSspModelStorageBySspModelIds($notFoundModelIds);
    }

    /**
     * @param list<int> $sspModelIds
     *
     * @return \Generated\Shared\Transfer\SspModelCriteriaTransfer
     */
    protected function createSspModelCriteriaTransfer(array $sspModelIds): SspModelCriteriaTransfer
    {
        $sspModelConditionsTransfer = (new SspModelConditionsTransfer())
            ->setSspModelIds($sspModelIds);

        return (new SspModelCriteriaTransfer())
            ->setSspModelConditions($sspModelConditionsTransfer)
            ->setWithProductLists(true);
    }
}
