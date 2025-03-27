<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Persistence;

use Generated\Shared\Transfer\FileCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryFile;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquirySalesOrder;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquirySspAsset;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementPersistenceFactory getFactory()
 */
class SspInquiryManagementEntityManager extends AbstractEntityManager implements SspInquiryManagementEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function createSspInquiry(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        return $this->saveSspInquiry($sspInquiryTransfer, new SpySspInquiry());
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer|null
     */
    public function updateSspInquiry(SspInquiryTransfer $sspInquiryTransfer): ?SspInquiryTransfer
    {
          $sspInquiryQuery = $this->getFactory()->createSspInquiryQuery();

          $sspInquiryEntity = $sspInquiryQuery->filterByIdSspInquiry($sspInquiryTransfer->getIdSspInquiry())->findOne();

        if (!$sspInquiryEntity) {
            return null;
        }

        return $this->saveSspInquiry($sspInquiryTransfer, $sspInquiryEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     * @param \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry $sspInquiryEntity
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    protected function saveSspInquiry(SspInquiryTransfer $sspInquiryTransfer, SpySspInquiry $sspInquiryEntity): SspInquiryTransfer
    {
          $sspInquiryEntity = $this->getFactory()->createSspInquiryMapper()->mapSspInquiryTransferToSspInquiryEntity($sspInquiryTransfer, $sspInquiryEntity);

        if ($sspInquiryTransfer->getStatus()) {
            $stateMachineItemState = $this->getFactory()->createStateMachineItemStateQuery()->findOneByName($sspInquiryTransfer->getStatus());
            if ($stateMachineItemState) {
                 $sspInquiryEntity->setFkStateMachineItemState($stateMachineItemState->getIdStateMachineItemState());
            }
        }

        if ($sspInquiryEntity->isNew() || $sspInquiryEntity->isModified()) {
             $sspInquiryEntity->save();
        }

         $sspInquiryTransfer = $this->getFactory()->createSspInquiryMapper()->mapSspInquiryEntityToSspInquiryTransfer($sspInquiryEntity, $sspInquiryTransfer);

        return $sspInquiryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function createSspInquiryFiles(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        foreach ($sspInquiryTransfer->getFiles() as $fileTransfer) {
             $sspInquiryFileEntity = (new SpySspInquiryFile())
                ->setFkFile($fileTransfer->getIdFileOrFail())
                ->setFkSspInquiry($sspInquiryTransfer->getIdSspInquiryOrFail());

             $sspInquiryFileEntity->save();
        }

        return $sspInquiryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function createSspInquirySalesOrder(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
         $sspInquirySalesOrderEntity = (new SpySspInquirySalesOrder())
            ->setFkSspInquiry($sspInquiryTransfer->getIdSspInquiryOrFail())
            ->setFkSalesOrder($sspInquiryTransfer->getOrderOrFail()->getIdSalesOrderOrFail());

         $sspInquirySalesOrderEntity->save();

        return $sspInquiryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function createSspInquirySspAsset(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        $sspInquirySspAssetEntity = (new SpySspInquirySspAsset())
            ->setFkSspInquiry($sspInquiryTransfer->getIdSspInquiryOrFail())
            ->setFkSspAsset($sspInquiryTransfer->getSspAssetOrFail()->getIdSspAssetOrFail());

        $sspInquirySspAssetEntity->save();

        return $sspInquiryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileCollectionTransfer $fileCollectionTransfer
     *
     * @return void
     */
    public function deleteSspInquiryFile(FileCollectionTransfer $fileCollectionTransfer): void
    {
        $fileIds = [];

        foreach ($fileCollectionTransfer->getFiles() as $fileTransfer) {
            $fileIds[] = $fileTransfer->getIdFileOrFail();
        }

        if (!$fileIds) {
            return;
        }

        $this->getFactory()->createSspInquiryFileQuery()->filterByFkFile_In($fileIds)->delete();
    }
}
