<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\NavigationStorageTransfer;
use Generated\Shared\Transfer\RestNavigationTreeAttributesTransfer;

interface NavigationMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\NavigationStorageTransfer $navigationStorageTransfer
     * @param \Generated\Shared\Transfer\RestNavigationTreeAttributesTransfer $restNavigationTreeAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestNavigationTreeAttributesTransfer
     */
    public function mapNavigationStorageTransferToRestNavigationTreeAttributesTransfer(
        NavigationStorageTransfer $navigationStorageTransfer,
        RestNavigationTreeAttributesTransfer $restNavigationTreeAttributesTransfer
    ): RestNavigationTreeAttributesTransfer;
}
