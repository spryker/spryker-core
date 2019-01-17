<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CompanyBusinessUnitAddressesRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_COMPANY_BUSINESS_UNIT_ADDRESSES = 'company-business-unit-addresses';
    public const CONTROLLER_RESOURCE_COMPANY_BUSINESS_UNIT_ADDRESSES = 'company-business-unit-addresses-resource';
    public const ACTION_COMPANY_BUSINESS_UNIT_ADDRESSES_GET = 'get';

    public const RESPONSE_CODE_COMPANY_BUSINESS_UNIT_ADDRESS_NOT_FOUND = '2001';
    public const RESPONSE_DETAIL_COMPANY_BUSINESS_UNIT_ADDRESS_NOT_FOUND = 'Company business unit address not found.';

    public const RESPONSE_CODE_COMPANY_BUSINESS_UNIT_ADDRESS_ID_IS_MISSING = '2002';
    public const RESPONSE_DETAIL_COMPANY_BUSINESS_UNIT_ADDRESS_ID_IS_MISSING = 'Company business unit address id is missing.';
}
