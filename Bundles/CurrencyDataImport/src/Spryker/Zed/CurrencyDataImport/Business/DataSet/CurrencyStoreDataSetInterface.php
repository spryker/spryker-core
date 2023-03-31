<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CurrencyDataImport\Business\DataSet;

interface CurrencyStoreDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_STORE_NAME = 'store_name';

    /**
     * @var string
     */
    public const COLUMN_CURRENCY_CODE = 'currency_code';

    /**
     * @var string
     */
    public const COLUMN_IS_DEFAULT = 'is_default';

    /**
     * @var string
     */
    public const ID_STORE = 'id_store';

    /**
     * @var string
     */
    public const ID_CURRENCY = 'id_currency';
}
