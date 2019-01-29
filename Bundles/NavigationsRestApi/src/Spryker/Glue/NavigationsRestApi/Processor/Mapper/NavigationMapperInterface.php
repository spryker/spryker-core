<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\NavigationStorageTransfer;
use Generated\Shared\Transfer\RestNavigationAttributesTransfer;

interface NavigationMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\NavigationStorageTransfer $navigationStorageTransfer
     * @param \Generated\Shared\Transfer\RestNavigationAttributesTransfer $restNavigationAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestNavigationAttributesTransfer
     */
    public function mapNavigationStorageTransferToRestNavigationAttributesTransfer(
        NavigationStorageTransfer $navigationStorageTransfer,
        RestNavigationAttributesTransfer $restNavigationAttributesTransfer
    ): RestNavigationAttributesTransfer;
}
