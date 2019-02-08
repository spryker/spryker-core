<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Business;

use Spryker\Zed\CompanyUsersRestApi\Business\Expander\CustomerIdentifierExpander;
use Spryker\Zed\CompanyUsersRestApi\Business\Expander\CustomerIdentifierExpanderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUsersRestApi\CompanyUsersRestApiConfig getConfig()
 */
class CompanyUsersRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyUsersRestApi\Business\Expander\CustomerIdentifierExpanderInterface
     */
    public function createCustomerIdentifierExpander(): CustomerIdentifierExpanderInterface
    {
        return new CustomerIdentifierExpander();
    }
}
