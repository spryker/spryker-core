<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CountryDataImport\Business\DataSet;

interface CountryStoreDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_STORE_NAME = 'store_name';

    /**
     * @var string
     */
    public const COLUMN_COUNTRY_NAME = 'country';

    /**
     * @var string
     */
    public const ID_STORE = 'id_store';

    /**
     * @var string
     */
    public const ID_COUNTRY = 'id_country';
}
