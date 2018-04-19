<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\PostRegistration;

use Generated\Shared\Transfer\CustomerTransfer;

interface CompanyUserCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return mixed
     */
    public function create(CustomerTransfer $customerTransfer): void;
}
