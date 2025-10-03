<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRegistrationRequest;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;

interface MerchantRegistrationRequestClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Creates a new merchant registration request.
     *
     * @api
     */
    public function createMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer;
}
