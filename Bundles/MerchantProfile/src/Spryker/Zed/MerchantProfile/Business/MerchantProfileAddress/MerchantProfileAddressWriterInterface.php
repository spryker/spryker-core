<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfileAddress;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProfileAddressTransfer;

interface MerchantProfileAddressWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function create(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): MerchantProfileAddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function update(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): MerchantProfileAddressTransfer;

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MerchantProfileAddressTransfer[] $merchantProfileAddressTransfers
     * @param int $idMerchantProfile
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer[]
     */
    public function saveMerchantProfileAddresses(
        ArrayObject $merchantProfileAddressTransfers,
        int $idMerchantProfile
    ): ArrayObject;
}
