<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\DataSet;

interface CompanyUnitAddressDataSet
{
    /**
     * @var string
     */
    public const ADDRESS_KEY = 'address_key';

    /**
     * @var string
     */
    public const COMPANY_KEY = 'company_key';

    /**
     * @var string
     */
    public const ID_COMPANY = 'idCompany';

    /**
     * @var string
     */
    public const COUNTRY_ISO_2_CODE = 'country_iso2_code';

    /**
     * @var string
     */
    public const COUNTRY_ISO_3_CODE = 'country_iso3_code';

    /**
     * @var string
     */
    public const ID_COUNTRY = 'idCountry';
}
