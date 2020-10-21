<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Sanitizer;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerImpersonationSanitizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function sanitizeCustomerImpersonation(CustomerTransfer $customerTransfer): void;
}
