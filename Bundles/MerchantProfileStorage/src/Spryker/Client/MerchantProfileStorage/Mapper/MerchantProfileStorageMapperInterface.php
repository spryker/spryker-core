<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage\Mapper;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;

interface MerchantProfileStorageMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer
     */
    public function mapMerchantProfileStorageDataToMerchantProfileStorageTransfer(array $data): MerchantProfileStorageTransfer;
}
