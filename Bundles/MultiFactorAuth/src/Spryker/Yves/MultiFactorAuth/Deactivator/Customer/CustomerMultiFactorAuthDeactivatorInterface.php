<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Deactivator\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\HttpFoundation\Request;

interface CustomerMultiFactorAuthDeactivatorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function deactivate(Request $request, CustomerTransfer $customerTransfer): void;
}
