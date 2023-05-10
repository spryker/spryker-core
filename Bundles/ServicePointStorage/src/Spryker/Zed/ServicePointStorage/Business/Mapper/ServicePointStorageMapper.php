<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Business\Mapper;

use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;

class ServicePointStorageMapper implements ServicePointStorageMapperInterface
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
    ): ServicePointStorageTransfer {
        return $servicePointStorageTransfer->fromArray($servicePointTransfer->toArray(), true);
    }
}
