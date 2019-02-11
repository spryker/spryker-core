<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CompanyBusinessUnitsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_COMPANY_BUSINESS_UNITS = 'company-business-units';

    public const CONTROLLER_RESOURCE_COMPANY_BUSINESS_UNITS = 'company-business-units-resource';

    public const ACTION_COMPANY_BUSINESS_UNITS_GET = 'get';

    public const RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED = 'Endpoint is not implemented.';
}
