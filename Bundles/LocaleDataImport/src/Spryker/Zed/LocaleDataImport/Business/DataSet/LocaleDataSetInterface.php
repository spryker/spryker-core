<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\LocaleDataImport\Business\DataSet;

interface LocaleDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_STORE_NAME = 'store_name';

    /**
     * @var string
     */
    public const COLUMN_LOCALE_NAME = 'locale_name';

    /**
     * @var string
     */
    public const ID_STORE = 'id_store';

    /**
     * @var string
     */
    public const ID_LOCALE = 'id_locale';
}
