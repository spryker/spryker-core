<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage\Mapper;

use Generated\Shared\Transfer\MerchantProfileViewTransfer;

interface MerchantProfileStorageMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MerchantProfileViewTransfer
     */
    public function mapMerchantProfileStorageDataToMerchantProfileViewTransfer(array $data): MerchantProfileViewTransfer;
}
