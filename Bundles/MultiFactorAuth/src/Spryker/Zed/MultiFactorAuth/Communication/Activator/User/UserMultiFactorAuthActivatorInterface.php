<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Activator\User;

use Generated\Shared\Transfer\UserTransfer;
use Symfony\Component\HttpFoundation\Request;

interface UserMultiFactorAuthActivatorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\UserTransfer $UserTransfer
     *
     * @return void
     */
    public function activate(Request $request, UserTransfer $UserTransfer): void;
}
