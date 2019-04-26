<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CompanyUsersRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_COMPANY_USERS = 'company-users';
    public const CONTROLLER_RESOURCE_COMPANY_USERS = 'company-users-resource';
    public const RESOURCE_COMPANY_USERS_GET_ACTION_NAME = 'get';

    public const RESPONSE_CODE_RESOURCE_NOT_IMPLEMENTED = '1402';
    public const RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED = 'Resource is not implemented.';

    public const CURRENT_USER_COLLECTION_IDENTIFIER = 'mine';

    /**
     * @uses \Spryker\Glue\CustomersRestApi\CustomersRestApiConfig::RESOURCE_CUSTOMERS
     */
    public const RESOURCE_CUSTOMERS = 'customers';
}
