<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\ProductAlternativeDataSet;

interface ProductAlternativeDataSetInterface
{
    /**
     * @var string
     */
    public const KEY_COLUMN_CONCRETE_SKU = 'concrete_sku';

    /**
     * @var string
     */
    public const FK_PRODUCT = 'fkProduct';

    /**
     * @var string
     */
    public const KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU = 'alternative_product_concrete_sku';

    /**
     * @var string
     */
    public const FK_PRODUCT_CONCRETE_ALTERNATIVE = 'fkProductConcreteAlternative';

    /**
     * @var string
     */
    public const KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU = 'alternative_product_abstract_sku';

    /**
     * @var string
     */
    public const FK_PRODUCT_ABSTRACT_ALTERNATIVE = 'fkProductAbstractAlternative';
}
