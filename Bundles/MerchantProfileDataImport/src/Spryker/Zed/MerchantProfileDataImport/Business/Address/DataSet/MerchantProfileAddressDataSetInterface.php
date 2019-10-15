<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProfileDataImport\Business\Address\DataSet;

interface MerchantProfileAddressDataSetInterface
{
    public const MERCHANT_KEY = 'merchant_key';
    public const KEY = 'key';
    public const COUNTRY_ISO2_CODE = 'country_iso2_code';
    public const COUNTRY_ISO3_CODE = 'country_iso3_code';
    public const ADDRESS1 = 'address1';
    public const ADDRESS2 = 'address2';
    public const ADDRESS3 = 'address3';
    public const CITY = 'city';
    public const ZIP_CODE = 'zip_code';
    public const ID_MERCHANT_PROFILE = 'id_merchant_profile';
    public const ID_COUNTRY = 'id_country';
}
