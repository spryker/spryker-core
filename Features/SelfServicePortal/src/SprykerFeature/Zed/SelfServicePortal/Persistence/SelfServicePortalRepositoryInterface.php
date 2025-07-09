<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence;

use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\ProductClassCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;

interface SelfServicePortalRepositoryInterface
{
    /**
     * @param list<int> $productConcreteIds
     *
     * @return array<int, list<int>>
     */
    public function getShipmentTypeIdsGroupedByIdProductConcrete(array $productConcreteIds): array;

    /**
     * @param list<int> $productConcreteIds
     * @param string $shipmentTypeName
     *
     * @return array<int, list<int>>
     */
    public function getProductIdsWithShipmentType(array $productConcreteIds, string $shipmentTypeName): array;

    /**
     * @return \Generated\Shared\Transfer\ProductClassCollectionTransfer
     */
    public function getProductClassCollection(): ProductClassCollectionTransfer;

    /**
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductClassTransfer>
     */
    public function getProductClassesByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    public function getProductClassesByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    public function getProductClassesByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param array<string> $skus
     *
     * @return array<string, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    public function getProductClassesForConcreteProductsBySkusIndexedBySku(array $skus): array;

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    public function getProductClassesGroupedBySalesOrderItemIds(array $salesOrderItemIds): array;

    /**
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCollectionTransfer
     */
    public function getServiceCollection(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function getFileAttachmentCollection(
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(
        SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
    ): SspInquiryCollectionTransfer;

    /**
     * @param array<int> $stateIds
     *
     * @return array<\Generated\Shared\Transfer\StateMachineItemTransfer>
     */
    public function getStateMachineItemsByStateIds(array $stateIds): array;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryFileCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryOrderCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquirySspAssetCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function getSspAssetCollection(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): SspAssetCollectionTransfer;

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<int, \Generated\Shared\Transfer\SspAssetTransfer>
     */
    public function getSspAssetsIndexedByIdSalesOrderItem(array $salesOrderItemIds): array;

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderItemProductClassesBySalesOrderItemIds(array $salesOrderItemIds): void;
}
