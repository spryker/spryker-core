<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointStorage\Mapper;

use Generated\Shared\Transfer\ServicePointStorageTransfer;

interface ServicePointStorageMapperInterface
{
    /**
     * @param array<string, mixed> $servicePointStorageData
     * @param \Generated\Shared\Transfer\ServicePointStorageTransfer $servicePointStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageTransfer
     */
    public function mapServicePointStorageDataToServicePointStorageTransfer(
        array $servicePointStorageData,
        ServicePointStorageTransfer $servicePointStorageCriteriaTransfer
    ): ServicePointStorageTransfer;
}
