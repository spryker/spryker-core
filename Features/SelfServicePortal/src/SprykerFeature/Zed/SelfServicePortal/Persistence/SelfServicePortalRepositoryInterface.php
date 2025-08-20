<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence;

use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductClassCollectionTransfer;
use Generated\Shared\Transfer\ProductClassCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspModelCollectionTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
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

    public function getProductClassCollection(ProductClassCriteriaTransfer $productClassCriteriaTransfer): ProductClassCollectionTransfer;

    public function getSspServiceCollection(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer;

    public function getFileAttachmentCollection(
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCollectionTransfer;

    public function getSspInquiryCollection(
        SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
    ): SspInquiryCollectionTransfer;

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getSalesOrderItemsByIds(array $salesOrderItemIds): array;

    /**
     * @param array<int> $stateIds
     *
     * @return array<\Generated\Shared\Transfer\StateMachineItemTransfer>
     */
    public function getStateMachineItemsByStateIds(array $stateIds): array;

    public function getSspInquiryFileCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer;

    public function getSspInquiryOrderCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer;

    public function getSspInquirySspAssetCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer;

    public function getSspAssetCollection(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): SspAssetCollectionTransfer;

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<\Generated\Shared\Transfer\SspAssetTransfer>
     */
    public function getSspAssetsBySalesOrderItemIds(array $salesOrderItemIds): array;

    /**
     * @deprecated Use getSspAssetsBySalesOrderItemIds() and business layer indexation instead.
     *
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

    public function getSspModelCollection(SspModelCriteriaTransfer $sspModelCriteriaTransfer): SspModelCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $sspModelIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSspModelStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $sspModelIds = []): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $sspAssetIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSspAssetStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $sspAssetIds = []): array;
}
