<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage\Mapper;

use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;

interface UrlStorageMerchantMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlStorageResourceMapTransfer
     */
    public function mapUrlStorageTransferToUrlStorageResourceMapTransfer(UrlStorageTransfer $urlStorageTransfer): UrlStorageResourceMapTransfer;
}
