<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileCollectionTransfer;
use Generated\Shared\Transfer\ProductClassCriteriaTransfer;
use Generated\Shared\Transfer\ProductClassTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SalesOrderItemSspAssetTransfer;
use Generated\Shared\Transfer\SspAssetSearchTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Generated\Shared\Transfer\SspModelCollectionTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryFile;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySalesOrder;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModel;
use Propel\Runtime\Exception\InvalidArgumentException;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalPersistenceFactory getFactory()
 */
class SelfServicePortalEntityManager extends AbstractEntityManager implements SelfServicePortalEntityManagerInterface
{
    public function createProductShipmentType(ProductConcreteTransfer $productConcreteTransfer, int $idShipmentType): void
    {
        $productShipmentTypeEntity = $this->getFactory()
            ->createProductShipmentTypeQuery()
            ->filterByFkProduct($productConcreteTransfer->getIdProductConcreteOrFail())
            ->filterByFkShipmentType($idShipmentType)
            ->findOneOrCreate();

        $productShipmentTypeEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param list<int> $shipmentTypeIds
     *
     * @return void
     */
    public function deleteProductShipmentTypes(
        ProductConcreteTransfer $productConcreteTransfer,
        array $shipmentTypeIds
    ): void {
        $this->getFactory()
            ->createProductShipmentTypeQuery()
            ->filterByFkProduct($productConcreteTransfer->getIdProductConcreteOrFail())
            ->filterByFkShipmentType_In($shipmentTypeIds)
            ->delete();
    }

    public function saveProductClassesForProduct(ProductClassCriteriaTransfer $productClassCriteriaTransfer): void
    {
        $productClassConditions = $productClassCriteriaTransfer->getProductClassConditions();

        if (!$productClassConditions) {
            return;
        }

        $productConcreteIds = $productClassConditions->getProductConcreteIds();
        $productClassIds = $productClassConditions->getProductClassIds();

        if (!$productConcreteIds || !$productClassIds) {
            return;
        }

        foreach ($productConcreteIds as $idProduct) {
            $this->deleteProductClassesByProductId($idProduct);

            foreach ($productClassIds as $idProductClass) {
                $productToProductClassEntity = $this->getFactory()->createProductToProductClassQuery()
                    ->filterByFkProduct($idProduct)
                    ->filterByFkProductClass($idProductClass)
                    ->findOneOrCreate();

                $productToProductClassEntity->save();
            }
        }
    }

    public function deleteProductClassesByProductId(int $idProduct): void
    {
        $this->getFactory()
            ->createProductToProductClassQuery()
            ->filterByFkProduct($idProduct)
            ->delete();
    }

    public function saveSalesOrderItemProductClass(int $idSalesOrderItem, ProductClassTransfer $productClassTransfer): void
    {
        $salesProductClassEntity = $this->getFactory()
            ->createSalesProductClassQuery()
            ->filterByName($productClassTransfer->getName())
            ->findOneOrCreate();

        if ($salesProductClassEntity->isNew()) {
            $salesProductClassEntity->save();
        }

        $salesOrderItemProductClassEntity = $this->getFactory()
            ->createSalesOrderItemProductClassQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->filterByFkSalesProductClass($salesProductClassEntity->getIdSalesProductClass())
            ->findOneOrCreate();

        if ($salesOrderItemProductClassEntity->isNew()) {
            $salesOrderItemProductClassEntity->save();
        }
    }

    public function deleteFileAttachmentCollection(
        FileAttachmentTransfer $fileAttachmentTransfer
    ): void {
        $idFile = $fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail();

        $businessUnitIds = array_map(
            static fn (CompanyBusinessUnitTransfer $businessUnitTransfer): int => $businessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
            $fileAttachmentTransfer->getBusinessUnitCollection()?->getCompanyBusinessUnits()?->getArrayCopy() ?? [],
        );

        $this->getFactory()->createCompanyBusinessUnitFileQuery()
            ->filterByFkCompanyBusinessUnit_In($businessUnitIds)
            ->filterByFkFile($idFile)
            ->delete();

        $companyUserIds = array_map(
            static fn (CompanyUserTransfer $companyUserTransfer): int => $companyUserTransfer->getIdCompanyUserOrFail(),
            $fileAttachmentTransfer->getCompanyUserCollection()?->getCompanyUsers()?->getArrayCopy() ?? [],
        );

        $this->getFactory()->createCompanyUserFileQuery()
            ->filterByFkCompanyUser_In($companyUserIds)
            ->filterByFkFile($idFile)
            ->delete();

        $sspAssetIds = array_map(
            static fn (SspAssetTransfer $sspAssetTransfer): int => $sspAssetTransfer->getIdSspAssetOrFail(),
            $fileAttachmentTransfer->getSspAssetCollection()?->getSspAssets()?->getArrayCopy() ?? [],
        );

        $this->getFactory()->createSspAssetFileQuery()
            ->filterByFkSspAsset_In($sspAssetIds)
            ->filterByFkFile($idFile)
            ->delete();
    }

    public function saveFileAttachment(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        $fileAttachmentSaver = $this->getFactory()->createFileAttachmentSaver();

        $fileAttachmentSaver->saveBusinessUnitFileAttachment($fileAttachmentTransfer);
        $fileAttachmentSaver->saveCompanyUserAttachment($fileAttachmentTransfer);
        $fileAttachmentSaver->saveSspAssetFileAttachment($fileAttachmentTransfer);

        return $fileAttachmentTransfer;
    }

    public function createSspInquiry(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        return $this->saveSspInquiry($sspInquiryTransfer, new SpySspInquiry());
    }

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

    public function createSspInquirySalesOrder(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        $sspInquirySalesOrderEntity = (new SpySspInquirySalesOrder())
            ->setFkSspInquiry($sspInquiryTransfer->getIdSspInquiryOrFail())
            ->setFkSalesOrder($sspInquiryTransfer->getOrderOrFail()->getIdSalesOrderOrFail());

        $sspInquirySalesOrderEntity->save();

        return $sspInquiryTransfer;
    }

    public function createSspInquirySspAsset(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        $sspInquirySspAssetEntity = (new SpySspInquirySspAsset())
            ->setFkSspInquiry($sspInquiryTransfer->getIdSspInquiryOrFail())
            ->setFkSspAsset($sspInquiryTransfer->getSspAssetOrFail()->getIdSspAssetOrFail());

        $sspInquirySspAssetEntity->save();

        return $sspInquiryTransfer;
    }

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

    public function createSspModel(SspModelTransfer $sspModelTransfer): SspModelTransfer
    {
        $spySspModelEntity = $this->getFactory()
            ->createModelMapper()
            ->mapSspModelTransferToSpySspModelEntity($sspModelTransfer, new SpySspModel());

        $spySspModelEntity->save();

        return $this->getFactory()
            ->createModelMapper()
            ->mapSpySspModelEntityToSspModelTransfer($spySspModelEntity, $sspModelTransfer);
    }

    public function updateSspModel(SspModelTransfer $sspModelTransfer): ?SspModelTransfer
    {
        $spySspModelEntity = $this->getFactory()
            ->createSspModelQuery()
            ->filterByIdSspModel($sspModelTransfer->getIdSspModelOrFail())
            ->findOne();

        if (!$spySspModelEntity) {
            return null;
        }

        $spySspModelEntity = $this->getFactory()
            ->createModelMapper()
            ->mapSspModelTransferToSpySspModelEntity($sspModelTransfer, $spySspModelEntity);

        $spySspModelEntity->save();

        return $this->getFactory()
            ->createModelMapper()
            ->mapSpySspModelEntityToSspModelTransfer($spySspModelEntity, $sspModelTransfer);
    }

    public function createSalesOrderItemSspAsset(SalesOrderItemSspAssetTransfer $salesOrderItemSspAssetTransfer): void
    {
        $salesOrderItemSspAssetEntity = new SpySalesOrderItemSspAsset();
        $salesOrderItemSspAssetEntity->fromArray($salesOrderItemSspAssetTransfer->toArray());
        $salesOrderItemSspAssetEntity->setFkSalesOrderItem($salesOrderItemSspAssetTransfer->getSalesOrderItemOrFail()->getIdSalesOrderItemOrFail());
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
        foreach ($businessUnitIds as $idCompanyBusinessUnit) {
            $assetCompanyBusinessUnitRelationEntity = $this->getFactory()
                ->createSspAssetToCompanyBusinessUnitQuery()
                ->filterByFkSspAsset($idSspAsset)
                ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
                ->findOneOrCreate();

            if ($assetCompanyBusinessUnitRelationEntity->isNew()) {
                $assetCompanyBusinessUnitRelationEntity->save();
            }
        }
    }

    public function deleteProductConcreteToProductClassRelations(int $idProductConcrete): void
    {
        $this->getFactory()
            ->createProductToProductClassQuery()
            ->filterByFkProduct($idProductConcrete)
            ->delete();
    }

    /**
     * @param int $idProductConcrete
     * @param array<int> $productClassIds
     *
     * @return void
     */
    public function saveProductConcreteProductClassRelations(int $idProductConcrete, array $productClassIds): void
    {
        foreach ($productClassIds as $idProductClass) {
            $productConcreteProductClassEntity = $this->getFactory()
                ->createProductToProductClassQuery()
                ->filterByFkProduct($idProductConcrete)
                ->filterByFkProductClass($idProductClass)
                ->findOneOrCreate();

            if ($productConcreteProductClassEntity->isNew()) {
                $productConcreteProductClassEntity->save();
            }
        }
    }

    public function saveSspModelStorage(SspModelTransfer $sspModelTransfer): void
    {
        $sspModelStorageEntity = $this->getFactory()
            ->createSspModelStorageQuery()
            ->filterByFkSspModel($sspModelTransfer->getIdSspModelOrFail())
            ->findOneOrCreate();

        $sspModelStorageEntity = $this->getFactory()
            ->createSspModelStorageEntityMapper()
            ->mapSspModelTransferToSspModelStorageEntity($sspModelTransfer, $sspModelStorageEntity);

        $sspModelStorageEntity->save();
    }

    /**
     * @param array<int> $sspModelIds
     *
     * @return void
     */
    public function deleteSspModelStorageBySspModelIds(array $sspModelIds): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $sspModelStorageCollection */
        $sspModelStorageCollection = $this->getFactory()
            ->createSspModelStorageQuery()
            ->filterByFkSspModel_In($sspModelIds)
            ->find();

        $sspModelStorageCollection->delete();
    }

    public function saveSspAssetStorage(SspAssetTransfer $sspAssetTransfer): void
    {
        $sspAssetStorageEntity = $this->getFactory()
            ->createSspAssetStorageQuery()
            ->filterByFkSspAsset($sspAssetTransfer->getIdSspAssetOrFail())
            ->findOneOrCreate();

        $sspAssetStorageEntity = $this->getFactory()
            ->createSspAssetStorageEntityMapper()
            ->mapSspAssetTransferToSspAssetStorageEntity($sspAssetTransfer, $sspAssetStorageEntity);

        $sspAssetStorageEntity->save();
    }

    /**
     * @param array<int> $sspAssetIds
     *
     * @return void
     */
    public function deleteSspAssetStorageBySspAssetIds(array $sspAssetIds): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $sspAssetStorageCollection */
        $sspAssetStorageCollection = $this->getFactory()
            ->createSspAssetStorageQuery()
            ->filterByFkSspAsset_In($sspAssetIds)
            ->find();

        $sspAssetStorageCollection->delete();
    }

    public function saveSspAssetSearch(SspAssetSearchTransfer $sspAssetSearchTransfer): SspAssetSearchTransfer
    {
        $sspAssetSearchEntity = $this->getFactory()
            ->createSspAssetSearchPropelQuery()
            ->filterByFkSspAsset($sspAssetSearchTransfer->getIdSspAssetOrFail())
            ->findOneOrCreate();

        $sspAssetSearchEntity->fromArray($sspAssetSearchTransfer->toArray());
        $sspAssetSearchEntity->save();

        return $sspAssetSearchTransfer;
    }

    /**
     * @param array<int> $sspAssetIds
     *
     * @return void
     */
    public function deleteSspAssetSearchBySspAssetIds(array $sspAssetIds): void
    {
        $this->getFactory()
            ->createSspAssetSearchPropelQuery()
            ->filterByFkSspAsset_In($sspAssetIds)
            ->delete();
    }

    public function deleteSspModels(SspModelCollectionTransfer $sspModelCollectionTransfer): void
    {
        $sspModelIds = [];
        foreach ($sspModelCollectionTransfer->getSspModels() as $sspModelTransfer) {
            $sspModelIds[] = $sspModelTransfer->getIdSspModelOrFail();
        }

        if ($sspModelIds) {
            $this->getFactory()
                ->createSspModelQuery()
                ->filterByIdSspModel_In($sspModelIds)
                ->delete();
        }
    }
}
