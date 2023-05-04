<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business\DataSet;

interface ServicePointAddressDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_REGION_ISO2_CODE = 'region_iso2_code';

    /**
     * @var string
     */
    public const COLUMN_COUNTRY_ISO2_CODE = 'country_iso2_code';

    /**
     * @var string
     */
    public const COLUMN_ID_SERVICE_POINT = 'id_service_point';

    /**
     * @var string
     */
    public const COLUMN_ID_REGION = 'id_region';

    /**
     * @var string
     */
    public const COLUMN_ID_COUNTRY = 'id_country';

    /**
     * @var string
     */
    public const COLUMN_ADDRESS3 = 'address3';
}
