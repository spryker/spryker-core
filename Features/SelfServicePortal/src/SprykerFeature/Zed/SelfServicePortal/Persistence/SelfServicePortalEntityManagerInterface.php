<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence;

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

interface SelfServicePortalEntityManagerInterface
{
    public function createProductShipmentType(ProductConcreteTransfer $productConcreteTransfer, int $idShipmentType): void;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param list<int> $shipmentTypeIds
     *
     * @return void
     */
    public function deleteProductShipmentTypes(
        ProductConcreteTransfer $productConcreteTransfer,
        array $shipmentTypeIds
    ): void;

    public function deleteProductClassesByProductId(int $idProduct): void;

    public function saveProductClassesForProduct(ProductClassCriteriaTransfer $productClassCriteriaTransfer): void;

    public function saveSalesOrderItemProductClass(int $idSalesOrderItem, ProductClassTransfer $productClassTransfer): void;

    public function deleteFileAttachmentCollection(
        FileAttachmentTransfer $fileAttachmentTransfer
    ): void;

    public function saveFileAttachment(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer;

    public function createSspInquiry(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer;

    public function createSspInquiryFiles(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer;

    public function createSspInquirySalesOrder(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer;

    public function createSspInquirySspAsset(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer;

    public function deleteSspInquiryFileRelation(FileCollectionTransfer $fileCollectionTransfer): void;

    public function createSspAsset(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer;

    public function updateSspAsset(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer;

    public function createSspModel(SspModelTransfer $sspModelTransfer): SspModelTransfer;

    public function updateSspModel(SspModelTransfer $sspModelTransfer): ?SspModelTransfer;

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

    public function deleteProductConcreteToProductClassRelations(int $idProductConcrete): void;

    /**
     * @param int $idProductConcrete
     * @param array<int> $productClassIds
     *
     * @return void
     */
    public function saveProductConcreteProductClassRelations(int $idProductConcrete, array $productClassIds): void;

    public function saveSspModelStorage(SspModelTransfer $sspModelTransfer): void;

    /**
     * @param array<int> $sspModelIds
     *
     * @return void
     */
    public function deleteSspModelStorageBySspModelIds(array $sspModelIds): void;

    public function saveSspAssetStorage(SspAssetTransfer $sspAssetTransfer): void;

    /**
     * @param array<int> $sspAssetIds
     *
     * @return void
     */
    public function deleteSspAssetStorageBySspAssetIds(array $sspAssetIds): void;

    public function saveSspAssetSearch(SspAssetSearchTransfer $sspAssetSearchTransfer): SspAssetSearchTransfer;

    /**
     * @param array<int> $sspAssetIds
     *
     * @return void
     */
    public function deleteSspAssetSearchBySspAssetIds(array $sspAssetIds): void;

    public function deleteSspModels(SspModelCollectionTransfer $sspModelCollectionTransfer): void;
}
