<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Business\Mapper;

use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;

interface ServicePointStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param \Generated\Shared\Transfer\ServicePointStorageTransfer $servicePointStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageTransfer
     */
    public function mapServicePointTransferToServicePointStorageTransfer(
        ServicePointTransfer $servicePointTransfer,
        ServicePointStorageTransfer $servicePointStorageTransfer
    ): ServicePointStorageTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     * @param \Generated\Shared\Transfer\ServiceTypeStorageTransfer $serviceTypeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageTransfer
     */
    public function mapServiceTypeTransferToServiceTypeStorageTransfer(
        ServiceTypeTransfer $serviceTypeTransfer,
        ServiceTypeStorageTransfer $serviceTypeStorageTransfer
    ): ServiceTypeStorageTransfer;
}
