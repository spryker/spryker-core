<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CompanyRolesRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_COMPANY_ROLES = 'company-roles';
    public const CONTROLLER_RESOURCE_COMPANY_ROLES = 'company-roles-resource';

    public const ACTION_COMPANY_ROLES_GET = 'get';

    public const RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED = 'Endpoint is not implemented.';
}
