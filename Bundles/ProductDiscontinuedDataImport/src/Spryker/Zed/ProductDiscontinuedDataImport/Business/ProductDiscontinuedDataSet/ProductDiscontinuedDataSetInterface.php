<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport\Business\ProductDiscontinuedDataSet;

interface ProductDiscontinuedDataSetInterface
{
    public const KEY_CONCRETE_SKU = 'sku_concrete';
    public const ID_PRODUCT = 'fkProduct';

    public const KEY_LOCALES = 'locales';
    public const KEY_NOTE = 'note';
    public const KEY_LOCALIZED_NOTES = 'localizedNotes';
}
