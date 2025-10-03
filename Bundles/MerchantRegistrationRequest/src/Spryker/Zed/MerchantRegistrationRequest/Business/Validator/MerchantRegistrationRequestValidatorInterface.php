<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business\Validator;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;

interface MerchantRegistrationRequestValidatorInterface
{
    public function validateMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer,
        MerchantRegistrationResponseTransfer $merchantRegistrationResponseTransfer
    ): MerchantRegistrationResponseTransfer;
}
