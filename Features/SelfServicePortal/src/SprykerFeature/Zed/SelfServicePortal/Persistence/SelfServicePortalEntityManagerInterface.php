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

interface SelfServicePortalEntityManagerInterface
{
    /**
     * @param int $idProductConcrete
     * @param int $idShipmentType
     *
     * @return void
     */
    public function createProductShipmentType(int $idProductConcrete, int $idShipmentType): void;

    /**
     * @param int $idProductConcrete
     * @param list<int> $shipmentTypeIds
     *
     * @return void
     */
    public function deleteProductShipmentTypesByIdProductConcreteAndShipmentTypeIds(
        int $idProductConcrete,
        array $shipmentTypeIds
    ): void;

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deleteProductAbstractTypesByProductAbstractId(int $idProductAbstract): void;

    /**
     * @param int $idProductAbstract
     * @param array<int> $productAbstractTypeIds
     *
     * @return void
     */
    public function updateProductAbstractTypesForProductAbstract(int $idProductAbstract, array $productAbstractTypeIds): void;

    /**
     * @param int $idSalesOrderItem
     * @param string $productTypeName
     *
     * @return void
     */
    public function saveSalesOrderItemProductType(int $idSalesOrderItem, string $productTypeName): void;

    /**
     * @param int $idSalesOrderItem
     * @param bool $isServiceDateTimeEnabled
     *
     * @return void
     */
    public function saveIsServiceDateTimeEnabledForSalesOrderItem(int $idSalesOrderItem, bool $isServiceDateTimeEnabled): void;

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deleteFileAttachmentCollection(
        FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    public function saveFileAttachment(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function createSspInquiry(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function createSspInquiryFiles(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function createSspInquirySalesOrder(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function createSspInquirySspAsset(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer;

    /**
     * @param \Generated\Shared\Transfer\FileCollectionTransfer $fileCollectionTransfer
     *
     * @return void
     */
    public function deleteSspInquiryFileRelation(FileCollectionTransfer $fileCollectionTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function createSspAsset(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function updateSspAsset(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemSspAssetTransfer $salesOrderItemSspAssetTransfer
     *
     * @return void
     */
    public function createSalesOrderItemSspAsset(SalesOrderItemSspAssetTransfer $salesOrderItemSspAssetTransfer): void;

    /**
     * @param int $idSspAsset
     * @param array<int> $businessUnitIds
     *
     * @return void
     */
    public function deleteAssetToCompanyBusinessUnitRelations(int $idSspAsset, array $businessUnitIds): void;

    /**
     * @param int $idSspAsset
     * @param array<int> $businessUnitIds
     *
     * @return void
     */
    public function createAssetToCompanyBusinessUnitRelation(int $idSspAsset, array $businessUnitIds): void;

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderItemProductAbstractTypesBySalesOrderItemIds(array $salesOrderItemIds): void;
}
