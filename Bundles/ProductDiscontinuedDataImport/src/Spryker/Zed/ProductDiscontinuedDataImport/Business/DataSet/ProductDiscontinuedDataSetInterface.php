<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport\Business\DataSet;

interface ProductDiscontinuedDataSetInterface
{
    public const KEY_CONCRETE_SKU = 'sku_concrete';
    public const ID_PRODUCT = 'fkProduct';

    public const KEY_LOCALES = 'locales';
    public const KEY_NOTE = 'note';
    public const KEY_LOCALIZED_NOTES = 'localizedNotes';
}
