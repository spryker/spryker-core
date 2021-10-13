<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProfileDataImport\Business\Address\DataSet;

interface MerchantProfileAddressDataSetInterface
{
    /**
     * @var string
     */
    public const MERCHANT_REFERENCE = 'merchant_reference';
    /**
     * @var string
     */
    public const COUNTRY_ISO2_CODE = 'country_iso2_code';
    /**
     * @var string
     */
    public const COUNTRY_ISO3_CODE = 'country_iso3_code';
    /**
     * @var string
     */
    public const ADDRESS1 = 'address1';
    /**
     * @var string
     */
    public const ADDRESS2 = 'address2';
    /**
     * @var string
     */
    public const ADDRESS3 = 'address3';
    /**
     * @var string
     */
    public const CITY = 'city';
    /**
     * @var string
     */
    public const ZIP_CODE = 'zip_code';
    /**
     * @var string
     */
    public const ID_MERCHANT_PROFILE = 'id_merchant_profile';
    /**
     * @var string
     */
    public const ID_COUNTRY = 'id_country';
    /**
     * @var string
     */
    public const LATITUDE = 'latitude';
    /**
     * @var string
     */
    public const LONGITUDE = 'longitude';
}
