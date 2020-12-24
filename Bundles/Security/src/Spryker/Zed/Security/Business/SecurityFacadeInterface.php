<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Business;

interface SecurityFacadeInterface
{
    /**
     * Specification:
     * - Checks if a user is logged-in.
     *
     * @api
     *
     * @return bool
     */
    public function isUserLoggedIn(): bool;
}
