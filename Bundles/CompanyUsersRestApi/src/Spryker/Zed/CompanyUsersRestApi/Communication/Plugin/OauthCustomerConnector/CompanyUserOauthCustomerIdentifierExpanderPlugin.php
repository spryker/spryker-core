<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Communication\Plugin\OauthCustomerConnector;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCustomerConnectorExtension\Dependency\Plugin\OauthCustomerIdentifierExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CompanyUsersRestApi\Business\CompanyUsersRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUsersRestApi\CompanyUsersRestApiConfig getConfig()
 * @method \Spryker\Zed\CompanyUsersRestApi\Communication\CompanyUsersRestApiCommunicationFactory getFactory()
 */
class CompanyUserOauthCustomerIdentifierExpanderPlugin extends AbstractPlugin implements OauthCustomerIdentifierExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the CustomerIdentifierTransfer with company user's uuid if it is set up in CustomerTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function expandCustomerIdentifier(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer {
        return $this->getFacade()->expandCustomerIdentifier($customerIdentifierTransfer, $customerTransfer);
    }
}
