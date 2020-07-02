<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage\Reader;

use Generated\Shared\Transfer\MerchantProductStorageTransfer;

interface MerchantProductStorageReaderInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\MerchantProductStorageTransfer|null
     */
    public function findOne(int $idProductAbstract): ?MerchantProductStorageTransfer;
}
