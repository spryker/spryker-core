<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence;

use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;

interface MerchantProfileEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function create(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @throws \Spryker\Zed\MerchantProfile\Persistence\Exception\MerchantProfileNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function update(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function createMerchantProfileAddress(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): MerchantProfileAddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function updateMerchantProfileAddress(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): MerchantProfileAddressTransfer;
}
