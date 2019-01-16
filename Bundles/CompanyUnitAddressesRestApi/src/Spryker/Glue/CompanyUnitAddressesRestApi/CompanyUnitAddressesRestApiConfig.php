<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUnitAddressesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CompanyUnitAddressesRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_COMPANY_UNIT_ADDRESSES = 'company-unit-addresses';
    public const CONTROLLER_RESOURCE_COMPANY_UNIT_ADDRESSES = 'company-unit-addresses-resource';
    public const ACTION_COMPANY_UNIT_ADDRESSES_GET = 'get';

    public const RESPONSE_CODE_COMPANY_UNIT_ADDRESS_NOT_FOUND = '1901';
    public const RESPONSE_DETAIL_COMPANY_UNIT_ADDRESS_NOT_FOUND = 'Company unit address not found.';

    public const RESPONSE_CODE_COMPANY_UNIT_ADDRESS_ID_IS_MISSING = '1902';
    public const RESPONSE_DETAIL_COMPANY_UNIT_ADDRESS_ID_IS_MISSING = 'Company unit address uuid is missing.';
}
