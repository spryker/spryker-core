<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\MerchantAddress;

use Generated\Shared\Transfer\MerchantAddressTransfer;

interface MerchantAddressReaderInterface
{
    /**
     * @param int $idMerchantAddress
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer|null
     */
    public function findMerchantAddressByIdMerchantAddress(int $idMerchantAddress): ?MerchantAddressTransfer;
}
