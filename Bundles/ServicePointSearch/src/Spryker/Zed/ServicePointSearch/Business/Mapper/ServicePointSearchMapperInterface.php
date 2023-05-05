<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Business\Mapper;

use Generated\Shared\Transfer\ServicePointSearchTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ServicePointSearchMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param \Generated\Shared\Transfer\ServicePointSearchTransfer $servicePointSearchTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointSearchTransfer
     */
    public function mapServicePointTransferToServicePointSearchTransfer(
        ServicePointTransfer $servicePointTransfer,
        ServicePointSearchTransfer $servicePointSearchTransfer,
        StoreTransfer $storeTransfer
    ): ServicePointSearchTransfer;
}
