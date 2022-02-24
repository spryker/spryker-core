<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission;

interface CustomerAccessPermissionClientInterface
{
    /**
     * Specification:
     * - Returns customer secured pattern with applied customer access rules on it.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\CustomerAccessPermission\Plugin\Customer\CustomerAccessSecuredPatternRulePlugin} plugin instead.
     *
     * @return string
     */
    public function getCustomerSecuredPatternForUnauthenticatedCustomerAccess(): string;
}
