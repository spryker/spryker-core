<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Business\Notifier;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\VerificationTokenCustomerChangeDataResponseTransfer;

interface NotificationEmailSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\VerificationTokenCustomerChangeDataResponseTransfer
     */
    public function send(CustomerTransfer $customerTransfer): VerificationTokenCustomerChangeDataResponseTransfer;
}
