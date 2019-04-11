<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnectorExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;

interface OauthCustomerScopeProviderPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function provideScopes(CustomerIdentifierTransfer $customerIdentifierTransfer): array;
}
