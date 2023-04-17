<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business;

use Generated\Shared\Transfer\GeneratePickingListsRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;

interface PickingListFacadeInterface
{
    /**
     * Specification:
     * - Retrieves picking list entities filtered by criteria.
     * - Uses `PickingListCriteriaTransfer.PickingListConditions.uuids` to filter pickingLists by uuids.
     * - Uses `PickingListCriteriaTransfer.PickingListConditions.userUuids` to filter pickingLists by user uuids.
     * - Uses `PickingListCriteriaTransfer.PickingListConditions.withUnassignedUser` to also include not assigned picking lists.
     * - Uses `PickingListCriteriaTransfer.PickingListConditions.warehouseUuids` to filter pickingLists by warehouse uuids.
     * - Uses `PickingListCriteriaTransfer.PickingListConditions.statuses` to filter pickingLists by statuses.
     * - Uses `PickingListCriteriaTransfer.PickingListConditions.salesOrderItemUuids` to filter pickingLists by sales order item uuids.
     * - Uses `PickingListCriteriaTransfer.SortTransfer.field` to set the `order by` field.
     * - Uses `PickingListCriteriaTransfer.SortTransfer.isAscending` to set ascending order otherwise will be used descending order.
     * - Uses `PickingListCriteriaTransfer.PaginationTransfer.{limit, offset}` to paginate result with limit and offset.
     * - Uses `PickingListCriteriaTransfer.PaginationTransfer.{page, maxPerPage}` to paginate result with page and maxPerPage.
     * - Executes the stack of {@link \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListCollectionExpanderPluginInterface} plugins.
     * - Returns `PickingListCollectionTransfer` filled with found pickingLists.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListCriteriaTransfer $pickingListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function getPickingListCollection(PickingListCriteriaTransfer $pickingListCriteriaTransfer): PickingListCollectionTransfer;

    /**
     * Specification:
     * - Requires `PickingListCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `PickingListCollectionRequestTransfer.pickingLists` to be set.
     * - Requires `PickingListTransfer.warehouse.idStock` to be set.
     * - Requires `PickingListItemTransfer.orderItem.uuid` to be set.
     * - Requires `PickingListItemTransfer.quantity` to be greater than zero.
     * - Requires `PickingListItemTransfer.numberOfPicked` and `PickingListItemTransfer.numberOfNotPicked` to be equal to zero.
     * - Fills `PickingList.modifiedAttributes` with the whole picking list attributes collection.
     * - Creates picking list entities in Persistence.
     * - Uses `PickingListCollectionRequestTransfer.isTransactional` to make transactional creation.
     * - Executes the stack of {@link \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostCreatePluginInterface} plugins.
     * - Breaks {@link \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostCreatePluginInterface} plugins execution after the first error occurrence.
     * - Persists nothing in case if at least one of {@link \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostCreatePluginInterface} plugins returns error.
     * - Returns `PickingListCollectionResponseTransfer.PickingListTransfer[]` filled with created pickingLists.
     * - Returns `PickingListCollectionResponseTransfer.ErrorTransfer[]` filled with validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function createPickingListCollection(
        PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
    ): PickingListCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `PickingListTransfer.uuid` to be set.
     * - Requires `PickingListTransfer.user.uuid` to be set.
     * - Requires `PickingListTransfer.warehouse.uuid` to be set.
     * - Requires `PickingListTransfer.user` to be the same if it was set previously.
     * - Requires `PickingListItemTransfer.uuid` to be set.
     * - Requires `PickingListItemTransfer.quantity` to be greater than zero.
     * - Requires `PickingListItemTransfer.numberOfPicked` and `PickingListItemTransfer.numberOfNotPicked` to be set.
     * - Requires one of `PickingListItemTransfer.numberOfPicked` or `PickingListItemTransfer.numberOfNotPicked` to be equal to zero.
     * - Requires `PickingListItemTransfer.numberOfPicked` to be equal to `PickingListItemTransfer.quantity` if not zero.
     * - Requires `PickingListItemTransfer.numberOfNotPicked` to be equal to `PickingListItemTransfer.quantity` if not zero.
     * - Compares picking list with a persisted version and fills `PickingList.modifiedAttributes`.
     * - Updates picking list entities in Persistence.
     * - Updates picking list item entities in Persistence.
     * - Uses `PickingListCollectionRequestTransfer.isTransactional` to make transactional update.
     * - Executes the stack of {@link \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostUpdatePluginInterface} plugins.
     * - Breaks {@link \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostCreatePluginInterface} plugins execution after the first error occurrence.
     * - Persists nothing in case if at least one of {@link \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostUpdatePluginInterface} plugins returns error.
     * - Returns `PickingListCollectionResponseTransfer.PickingListTransfer[]` filled with updated pickingLists.
     * - Returns `PickingListCollectionResponseTransfer.ErrorTransfer[]` filled with validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function updatePickingListCollection(
        PickingListCollectionRequestTransfer $pickingListCollectionRequestTransfer
    ): PickingListCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `GeneratePickingListsRequest.orderItems` to be set.
     * - Requires `GeneratePickingListsRequest.orderItems.uuid` to be set.
     * - Requires `GeneratePickingListsRequest.orderItems.warehouse` to be set.
     * - Requires `GeneratePickingListsRequest.orderItems.warehouse.idStock` to be set.
     * - Requires `GeneratePickingListsRequest.orderItems.warehouse.pickingListStrategy` to be set.
     * - Groups order items by warehouse.
     * - Executes the stack of {@link \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface} plugins.
     * - Persists picking lists {@uses \Spryker\Zed\PickingList\Business\PickingListFacadeInterface::createPickingListCollection()}.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer
     *
     * @throws \Spryker\Zed\PickingList\Business\Exception\PickingListStrategyNotFoundException if `StockTransfer.pickingListStrategy` is missing.
     * @throws \Spryker\Zed\PickingList\Business\Exception\WarehouseNotFoundException if `PickingListTransfer.warehouse` is set but not found.
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function generatePickingLists(GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer): PickingListCollectionResponseTransfer;

    /**
     * Specification:
     * - Checks if picking lists generation is finished for the given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPickingListGenerationFinishedForOrder(OrderTransfer $orderTransfer): bool;

    /**
     * Specification:
     * - Checks if picking of at least one picking list is started for the given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPickingStartedForOrder(OrderTransfer $orderTransfer): bool;

    /**
     * Specification:
     * - Checks if all picking lists are finished for the given order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPickingFinishedForOrder(OrderTransfer $orderTransfer): bool;

    /**
     * Specification:
     * - Does nothing if `UserCollectionTransfer.user.isWarehouseUser` transfer property is not set to `true`.
     * - Does nothing if `UserCollectionTransfer.user.status` property is not `blocked` or `deleted`.
     * - Requires `UserCollectionTransfer.user.uuid` transfer property to be set.
     * - Finds picking lists assigned to provided user by `UserCollectionTransfer.user.uuid` transfer property.
     * - Removes user assignment from found picking lists.
     * - Persists updated picking lists.
     * - Returns unmodified `UserCollectionTransfer` object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function unassignPickingListsFromUsers(UserCollectionTransfer $userCollectionTransfer): UserCollectionTransfer;
}
