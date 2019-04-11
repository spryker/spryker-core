<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Business;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;

interface OauthPermissionFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function provideScopesByCustomerIdentifier(CustomerIdentifierTransfer $customerIdentifierTransfer): array;
}
