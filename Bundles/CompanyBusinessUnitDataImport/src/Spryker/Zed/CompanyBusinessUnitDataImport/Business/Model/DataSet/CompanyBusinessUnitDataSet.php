<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\DataSet;

interface CompanyBusinessUnitDataSet
{
    public const BUSINESS_UNIT_KEY = 'business_unit_key';

    public const COMPANY_KEY = 'company_key';
    public const ID_COMPANY = 'idCompany';

    public const PARENT_BUSINESS_UNIT_KEY = 'parent_business_unit_key';
    public const FK_PARENT_BUSINESS_UNIT = 'fk_parent_company_business_unit';
}
