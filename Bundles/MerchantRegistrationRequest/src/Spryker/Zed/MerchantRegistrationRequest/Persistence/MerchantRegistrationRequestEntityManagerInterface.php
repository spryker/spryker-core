<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Persistence;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;

interface MerchantRegistrationRequestEntityManagerInterface
{
    public function createMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationRequestTransfer;

    public function updateMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationRequestTransfer;
}
