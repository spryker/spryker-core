<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Acceptance\ProductOption\Zed\PageObject;

class ProductOptionCreatePage
{

    const URL = '/product-option/create/index';

    const PRODUCT_OPTION_CREATED_SUCCESS_MESSAGE = 'Product option group created.';

    const VALID_GROUP = 'valid_group';

    /**
     * @var array
     */
    public static $productOptionGroupData = [
        self::VALID_GROUP => [
            'group_name_translation_key' => 'test_product_option_name_translation_key',
            'fk_tax_set' => 1,
            'values' => [
                [
                    'value_translation_key' => 'option_value_1_translation_key',
                    'value_sku' => 'option_value_1_sku_',
                    'value_price' => '12,34',
                ],
                [
                    'value_translation_key' => 'option_value_2_translation_key',
                    'value_sku' => 'option_value_2_sku_',
                    'value_price' => '12.34',
                ]
            ]
        ],
    ];

}
