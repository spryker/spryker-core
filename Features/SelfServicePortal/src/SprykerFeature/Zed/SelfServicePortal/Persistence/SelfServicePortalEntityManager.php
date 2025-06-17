<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence;

use Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemSspAssetTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use LogicException;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractToProductAbstractType;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnit;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryFile;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySalesOrder;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAsset;
use Propel\Runtime\Exception\InvalidArgumentException;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalPersistenceFactory getFactory()
 */
class SelfServicePortalEntityManager extends AbstractEntityManager implements SelfServicePortalEntityManagerInterface
{
    /**
     * @param int $idProductConcrete
     * @param int $idShipmentType
     *
     * @return void
     */
    public function createProductShipmentType(int $idProductConcrete, int $idShipmentType): void
    {
        $productShipmentTypeEntity = $this->getFactory()
            ->createProductShipmentTypeQuery()
            ->filterByFkProduct($idProductConcrete)
            ->filterByFkShipmentType($idShipmentType)
            ->findOneOrCreate();

        $productShipmentTypeEntity->save();
    }

    /**
     * @param int $idProductConcrete
     * @param list<int> $shipmentTypeIds
     *
     * @return void
     */
    public function deleteProductShipmentTypesByIdProductConcreteAndShipmentTypeIds(
        int $idProductConcrete,
        array $shipmentTypeIds
    ): void {
        $this->getFactory()
            ->createProductShipmentTypeQuery()
            ->filterByFkProduct($idProductConcrete)
            ->filterByFkShipmentType_In($shipmentTypeIds)
            ->delete();
    }

    /**
     * @param int $idProductAbstract
     * @param array<int> $productAbstractTypeIds
     *
     * @return void
     */
    public function updateProductAbstractTypesForProductAbstract(int $idProductAbstract, array $productAbstractTypeIds): void
    {
        $this->deleteProductAbstractTypesByProductAbstractId($idProductAbstract);

        foreach ($productAbstractTypeIds as $idProductAbstractType) {
            $productAbstractToProductAbstractTypeEntity = new SpyProductAbstractToProductAbstractType();
            $productAbstractToProductAbstractTypeEntity
                ->setFkProductAbstract($idProductAbstract)
                ->setFkProductAbstractType($idProductAbstractType)
                ->save();
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deleteProductAbstractTypesByProductAbstractId(int $idProductAbstract): void
    {
        /**
         * @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractToProductAbstractType> $productAbstractTypesProductAbstractRelations
         */
        $productAbstractTypesProductAbstractRelations = $this->getFactory()
            ->createProductAbstractToProductAbstractTypeQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->find();

        $productAbstractTypesProductAbstractRelations->delete();
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $productTypeName
     *
     * @return void
     */
    public function saveSalesOrderItemProductType(int $idSalesOrderItem, string $productTypeName): void
    {
        $salesProductAbstractTypeEntity = $this->getFactory()
            ->createSalesProductAbstractTypeQuery()
            ->filterByName($productTypeName)
            ->findOneOrCreate();

        if ($salesProductAbstractTypeEntity->isNew()) {
            $salesProductAbstractTypeEntity->save();
        }

        $salesOrderItemProductAbstractTypeEntity = $this->getFactory()
            ->createSalesOrderItemProductAbstractTypeQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->filterByFkSalesProductAbstractType($salesProductAbstractTypeEntity->getIdSalesProductAbstractType())
            ->findOneOrCreate();

        if ($salesOrderItemProductAbstractTypeEntity->isNew()) {
            $salesOrderItemProductAbstractTypeEntity->save();
        }
    }

    /**
     * @param int $idSalesOrderItem
     * @param bool $isServiceDateTimeEnabled
     *
     * @return void
     */
    public function saveIsServiceDateTimeEnabledForSalesOrderItem(int $idSalesOrderItem, bool $isServiceDateTimeEnabled): void
    {
        $salesOrderItemEntity = $this->getFactory()
            ->getSalesOrderItemPropelQuery()
            ->filterByIdSalesOrderItem($idSalesOrderItem)
            ->findOne();

        if ($salesOrderItemEntity) {
            $salesOrderItemEntity->setIsServiceDateTimeEnabled($isServiceDateTimeEnabled);
            $salesOrderItemEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function deleteFileAttachmentCollection(
        FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
    ): void {
        /** @var list<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery> $fileAttachmentQueryList */
        $fileAttachmentQueryList = $this->getFactory()->getFileAttachmentQueryList();
        $isUnconditionalDeletion = true;

        if ($fileAttachmentCollectionDeleteCriteriaTransfer->getCompanyIds() !== []) {
            $isUnconditionalDeletion = false;
            $fileAttachmentQueryList = $this->applyFileAttachmentByCompanyIdsCondition($fileAttachmentQueryList, $fileAttachmentCollectionDeleteCriteriaTransfer->getCompanyIds());
        }

        if ($fileAttachmentCollectionDeleteCriteriaTransfer->getCompanyUserIds() !== []) {
            $isUnconditionalDeletion = false;
            $fileAttachmentQueryList = $this->applyFileAttachmentByCompanyUserIdsCondition($fileAttachmentQueryList, $fileAttachmentCollectionDeleteCriteriaTransfer->getCompanyUserIds());
        }

        if ($fileAttachmentCollectionDeleteCriteriaTransfer->getCompanyBusinessUnitIds() !== []) {
            $isUnconditionalDeletion = false;
            $fileAttachmentQueryList = $this->applyFileAttachmentByCompanyBusinessUnitIdsCondition($fileAttachmentQueryList, $fileAttachmentCollectionDeleteCriteriaTransfer->getCompanyBusinessUnitIds());
        }

        if ($fileAttachmentCollectionDeleteCriteriaTransfer->getSspAssetIds() !== []) {
            $isUnconditionalDeletion = false;
            $fileAttachmentQueryList = $this->applyFileAttachmentByAssetIdsCondition($fileAttachmentQueryList, $fileAttachmentCollectionDeleteCriteriaTransfer->getSspAssetIds());
        }

        if ($fileAttachmentCollectionDeleteCriteriaTransfer->getFileIds() !== []) {
            $isUnconditionalDeletion = false;
            $fileAttachmentQueryList = $this->applyFileAttachmentByFileIdsCondition(
                $fileAttachmentQueryList,
                $fileAttachmentCollectionDeleteCriteriaTransfer->getFileIds(),
                count($fileAttachmentCollectionDeleteCriteriaTransfer->modifiedToArray()) > 1,
            );
        }

        if ($isUnconditionalDeletion && !$fileAttachmentCollectionDeleteCriteriaTransfer->getIsUnconditionalDeletionAllowed()) {
            throw new LogicException('Unconditional deletion is not allowed.');
        }

        $this->deleteFileAttachments($fileAttachmentQueryList);
    }

    /**
     * @param list<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery> $fileAttachmentQueryList
     * @param list<int> $fileIds
     * @param bool $applyOnlyToModifiedQueries
     *
     * @return list<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery>
     */
    protected function applyFileAttachmentByFileIdsCondition(
        array $fileAttachmentQueryList,
        array $fileIds,
        bool $applyOnlyToModifiedQueries
    ): array {
        foreach ($fileAttachmentQueryList as $fileAttachmentQuery) {
            if ($applyOnlyToModifiedQueries && !$fileAttachmentQuery->hasWhereClause()) {
                continue;
            }

            $fileAttachmentQuery->filterByFkFile_In($fileIds);
        }

        return $fileAttachmentQueryList;
    }

    /**
     * @param list<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery> $fileAttachmentQueryList
     * @param list<int> $companyIds
     *
     * @return list<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery>
     */
    protected function applyFileAttachmentByCompanyIdsCondition(
        array $fileAttachmentQueryList,
        array $companyIds
    ): array {
        foreach ($fileAttachmentQueryList as $fileAttachmentQuery) {
            if ($fileAttachmentQuery instanceof SpyCompanyFileQuery) {
                $fileAttachmentQuery->filterByFkCompany_In($companyIds);
            }
        }

        return $fileAttachmentQueryList;
    }

    /**
     * @param list<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery> $fileAttachmentQueryList
     * @param list<int> $companyUserIds
     *
     * @return list<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery>
     */
    protected function applyFileAttachmentByCompanyUserIdsCondition(
        array $fileAttachmentQueryList,
        array $companyUserIds
    ): array {
        foreach ($fileAttachmentQueryList as $fileAttachmentQuery) {
            if ($fileAttachmentQuery instanceof SpyCompanyUserFileQuery) {
                $fileAttachmentQuery->filterByFkCompanyUser_In($companyUserIds);
            }
        }

        return $fileAttachmentQueryList;
    }

    /**
     * @param list<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery> $fileAttachmentQueryList
     * @param list<int> $companyBusinessUnitIds
     *
     * @return list<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery>
     */
    protected function applyFileAttachmentByCompanyBusinessUnitIdsCondition(
        array $fileAttachmentQueryList,
        array $companyBusinessUnitIds
    ): array {
        foreach ($fileAttachmentQueryList as $fileAttachmentQuery) {
            if ($fileAttachmentQuery instanceof SpyCompanyBusinessUnitFileQuery) {
                $fileAttachmentQuery->filterByFkCompanyBusinessUnit_In($companyBusinessUnitIds);
            }
        }

        return $fileAttachmentQueryList;
    }

    /**
     * @param list<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery> $fileAttachmentQueryList
     * @param list<int> $assetIds
     *
     * @return list<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery>
     */
    protected function applyFileAttachmentByAssetIdsCondition(
        array $fileAttachmentQueryList,
        array $assetIds
    ): array {
        foreach ($fileAttachmentQueryList as $fileAttachmentQuery) {
            if ($fileAttachmentQuery instanceof SpySspAssetFileQuery) {
                $fileAttachmentQuery->filterByFkSspAsset_In($assetIds);
            }
        }

        return $fileAttachmentQueryList;
    }

    /**
     * @param list<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery|\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery> $fileAttachmentQueryList
     *
     * @return void
     */
    protected function deleteFileAttachments(array $fileAttachmentQueryList): void
    {
        foreach ($fileAttachmentQueryList as $fileAttachmentQuery) {
            if (!$fileAttachmentQuery->hasWhereClause()) {
                continue;
            }

            $fileAttachmentQuery->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @throws \LogicException
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    public function saveFileAttachment(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        $fileAttachmentSaver = null;

        foreach ($this->getFactory()->createFileAttachmentSavers() as $fileAttachmentSaverOption) {
            if ($fileAttachmentSaverOption->isApplicable($fileAttachmentTransfer)) {
                $fileAttachmentSaver = $fileAttachmentSaverOption;
            }
        }

        if (!$fileAttachmentSaver) {
            throw new LogicException(sprintf('Saver for entity "%s" is not implemented.', $fileAttachmentTransfer->getEntityName()));
        }

        return $fileAttachmentSaver->save($fileAttachmentTransfer);
    }

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
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry $sspInquiryEntity
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    protected function saveSspInquiry(SspInquiryTransfer $sspInquiryTransfer, SpySspInquiry $sspInquiryEntity): SspInquiryTransfer
    {
        $sspInquiryEntity = $this->getFactory()->createSspInquiryMapper()->mapSspInquiryTransferToSspInquiryEntity($sspInquiryTransfer, $sspInquiryEntity);

        if ($sspInquiryTransfer->getStatus()) {
            $stateMachineItemState = $this->getFactory()->getStateMachineItemStatePropelQuery()->findOneByName($sspInquiryTransfer->getStatus());
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
    public function deleteSspInquiryFileRelation(FileCollectionTransfer $fileCollectionTransfer): void
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

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function createSspAsset(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer
    {
        $spySspAssetEntity = $this->getFactory()
            ->createAssetMapper()
            ->mapSspAssetTransferToSpySspAssetEntity($sspAssetTransfer, new SpySspAsset());

        $spySspAssetEntity->save();
        $sspAssetTransfer->setIdSspAsset($spySspAssetEntity->getIdSspAsset());

        return $this->getFactory()
            ->createAssetMapper()
            ->mapSpySspAssetEntityToSspAssetTransfer($spySspAssetEntity, $sspAssetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @throws \Propel\Runtime\Exception\InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function updateSspAsset(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer
    {
        $spySspAssetEntity = $this->getFactory()
            ->createSspAssetQuery()
            ->findOneByIdSspAsset($sspAssetTransfer->getIdSspAssetOrFail());

        if (!$spySspAssetEntity) {
            throw new InvalidArgumentException('Ssp Asset not found');
        }

        $spySspAssetEntity = $this->getFactory()
            ->createAssetMapper()
            ->mapSspAssetTransferToSpySspAssetEntity($sspAssetTransfer, $spySspAssetEntity);

        if ($spySspAssetEntity->isModified()) {
            $spySspAssetEntity->save();
        }

        return $this->getFactory()
            ->createAssetMapper()
            ->mapSpySspAssetEntityToSspAssetTransfer($spySspAssetEntity, $sspAssetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemSspAssetTransfer $salesOrderItemSspAssetTransfer
     *
     * @return void
     */
    public function createSalesOrderItemSspAsset(SalesOrderItemSspAssetTransfer $salesOrderItemSspAssetTransfer): void
    {
        $salesOrderItemSspAssetEntity = new SpySalesOrderItemSspAsset();
        $salesOrderItemSspAssetEntity->fromArray($salesOrderItemSspAssetTransfer->toArray());
        $salesOrderItemSspAssetEntity->save();
    }

    /**
     * @param int $idSspAsset
     * @param array<int> $businessUnitIds
     *
     * @return void
     */
    public function deleteAssetToCompanyBusinessUnitRelations(int $idSspAsset, array $businessUnitIds): void
    {
        SpySspAssetToCompanyBusinessUnitQuery::create()
            ->filterByFkSspAsset($idSspAsset)
            ->filterByFkCompanyBusinessUnit_In($businessUnitIds)
            ->delete();
    }

    /**
     * @param int $idSspAsset
     * @param array<int> $businessUnitIds
     *
     * @return void
     */
    public function createAssetToCompanyBusinessUnitRelation(int $idSspAsset, array $businessUnitIds): void
    {
        foreach ($businessUnitIds as $businessUnitId) {
            $spySspAssetToCompanyBusinessUnit = new SpySspAssetToCompanyBusinessUnit();
            $spySspAssetToCompanyBusinessUnit
                ->setFkSspAsset($idSspAsset)
                ->setFkCompanyBusinessUnit($businessUnitId)
                ->save();
        }
    }

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderItemProductAbstractTypesBySalesOrderItemIds(array $salesOrderItemIds): void
    {
        if (!$salesOrderItemIds) {
            return;
        }

        /**
         * @var \Propel\Runtime\Collection\ObjectCollection $salesOrderItemProductAbstractTypeEntityCollection
         */
        $salesOrderItemProductAbstractTypeEntityCollection = $this->getFactory()
            ->createSalesOrderItemProductAbstractTypeQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->find();

        $salesOrderItemProductAbstractTypeEntityCollection->delete();
    }
}
