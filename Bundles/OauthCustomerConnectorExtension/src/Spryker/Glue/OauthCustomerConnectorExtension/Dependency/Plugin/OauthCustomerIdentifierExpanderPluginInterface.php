<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\GLue\OauthCustomerConnectorExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;

interface OauthCustomerIdentifierExpanderPluginInterface
{
    /**
     * Specification:
     * - Can be used to extend the CustomerIdentifierTransfer with extra information.
     *
     * @api
     *
     * TODO: annotation
     */
    public function expandCustomerIdentifier(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfe
    ): CustomerIdentifierTransfer;
}
