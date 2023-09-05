<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointStorage\Mapper;

use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageTransfer;

interface ServicePointStorageMapperInterface
{
    /**
     * @param array<string, mixed> $servicePointStorageData
     * @param \Generated\Shared\Transfer\ServicePointStorageTransfer $servicePointStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageTransfer
     */
    public function mapServicePointStorageDataToServicePointStorageTransfer(
        array $servicePointStorageData,
        ServicePointStorageTransfer $servicePointStorageTransfer
    ): ServicePointStorageTransfer;

    /**
     * @param array<string, mixed> $serviceTypeStorageData
     * @param \Generated\Shared\Transfer\ServiceTypeStorageTransfer $serviceTypeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageTransfer
     */
    public function mapServiceTypeStorageDataToServiceTypeStorageTransfer(
        array $serviceTypeStorageData,
        ServiceTypeStorageTransfer $serviceTypeStorageTransfer
    ): ServiceTypeStorageTransfer;
}
