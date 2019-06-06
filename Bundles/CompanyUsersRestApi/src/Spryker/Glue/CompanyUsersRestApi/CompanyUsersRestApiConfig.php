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

    /**
     * @uses \Spryker\Glue\CompanyBusinessUnitsRestApi\CompanyBusinessUnitsRestApiConfig::RESOURCE_COMPANY_BUSINESS_UNITS
     */
    public const RESOURCE_COMPANY_BUSINESS_UNITS = 'company-business-units';

    /**
     * @uses \Spryker\Glue\CompanyRolesRestApi\CompanyRolesRestApiConfig::RESOURCE_COMPANY_ROLES
     */
    public const RESOURCE_COMPANY_ROLES = 'company-roles';

    public const RESPONSE_CODE_COMPANY_USER_NOT_SELECTED = '1403';
    public const RESPONSE_DETAIL_COMPANY_USER_NOT_SELECTED = 'Current company user is not set. You need to select the current company user with /company-user-access-tokens in order to access the resource collection.';

    public const RESPONSE_CODE_COMPANY_USER_NOT_FOUND = '1404';
    public const RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND = 'Company user not found';

    /**
     * @uses \Spryker\Glue\GlueApplication\GlueApplicationConfig::COLLECTION_IDENTIFIER_CURRENT_USER
     */
    public const COLLECTION_IDENTIFIER_CURRENT_USER = 'mine';
}
