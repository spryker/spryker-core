<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission;

interface CustomerAccessPermissionClientInterface
{
    /**
     * Specification
     * - Returns customer secured pattern with applied customer access rules on it.
     *
     * @api
     *
     * @return string
     */
    public function getCustomerSecuredPatternForUnauthenticatedCustomerAccess(): string;

    /**
     * Specification
     * - Return if not logined user can see product price
     *
     * @api
     *
     * @param string $key
     *
     * @return bool
     */
    public function loggedOutCustomerCan(string $key): bool;
}
