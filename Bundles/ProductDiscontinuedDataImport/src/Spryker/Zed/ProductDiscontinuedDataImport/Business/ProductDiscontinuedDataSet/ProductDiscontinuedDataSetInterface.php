<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport\Business\ProductDiscontinuedDataSet;

interface ProductDiscontinuedDataSetInterface
{
    /**
     * @var string
     */
    public const KEY_CONCRETE_SKU = 'sku_concrete';

    /**
     * @var string
     */
    public const ID_PRODUCT = 'fkProduct';

    /**
     * @var string
     */
    public const KEY_LOCALES = 'locales';

    /**
     * @var string
     */
    public const KEY_NOTE = 'note';

    /**
     * @var string
     */
    public const KEY_LOCALIZED_NOTES = 'localizedNotes';
}
