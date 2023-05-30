<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Persistence;

use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;

interface ServicePointEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createServicePoint(ServicePointTransfer $servicePointTransfer): ServicePointTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer
     */
    public function createServicePointAddress(ServicePointAddressTransfer $servicePointAddressTransfer): ServicePointAddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function updateServicePoint(ServicePointTransfer $servicePointTransfer): ServicePointTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer
     */
    public function updateServicePointAddress(ServicePointAddressTransfer $servicePointAddressTransfer): ServicePointAddressTransfer;

    /**
     * @param int $idServicePoint
     * @param array<int, int> $storeIds
     *
     * @return void
     */
    public function createServicePointStores(int $idServicePoint, array $storeIds): void;

    /**
     * @param int $idServicePoint
     * @param array<int, int> $storeIds
     *
     * @return void
     */
    public function deleteServicePointStores(int $idServicePoint, array $storeIds): void;

    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    public function createService(ServiceTransfer $serviceTransfer): ServiceTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    public function updateService(ServiceTransfer $serviceTransfer): ServiceTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    public function createServiceType(ServiceTypeTransfer $serviceTypeTransfer): ServiceTypeTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    public function updateServiceType(ServiceTypeTransfer $serviceTypeTransfer): ServiceTypeTransfer;
}
