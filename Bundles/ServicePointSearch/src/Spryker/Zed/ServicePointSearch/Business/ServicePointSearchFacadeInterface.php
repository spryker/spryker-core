<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ServicePointSearchFacadeInterface
{
    /**
     * Specification:
     * - Retrieves all Service Points using IDs from `$eventTransfers`.
     * - Updates entities from `spy_service_point_search` with actual data from obtained Service Points.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServicePointEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Retrieves all Service Points using `spy_service_point_address.fk_service_point` from `$eventTransfers`.
     * - Updates entities from `spy_service_point_search` with actual data from obtained Service Points.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServicePointAddressEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Retrieves all Service Points using `spy_service_point_store.fk_service_point` from `$eventTransfers`.
     * - Updates entities from `spy_service_point_search` with actual data from obtained Service Points.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServicePointStoreEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Retrieves all Service Points using `spy_service.fk_service_point` from `$eventTransfers`.
     * - Updates entities from `spy_service_point_search` with actual data from obtained Service Points.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServiceEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Deletes entities from `spy_service_point_search` based on IDs from `$eventTransfers`.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByServicePointEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Reads entities from `spy_service_point_search` based on criteria from `FilterTransfer` and `$servicePointIds`.
     * - Returns array of `SynchronizationDataTransfer` filled with data from search entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $servicePointIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getServicePointSynchronizationDataTransfersByIds(FilterTransfer $filterTransfer, array $servicePointIds = []): array;
}
