<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Activator\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\HttpFoundation\Request;

interface CustomerMultiFactorAuthActivatorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function activate(Request $request, CustomerTransfer $customerTransfer): void;
}
