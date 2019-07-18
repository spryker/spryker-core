<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\PageObject;

class ProductOptionCreatePage
{
    public const URL = '/product-option/create/index';

    public const PRODUCT_OPTION_CREATED_SUCCESS_MESSAGE = 'Product option group created.';

    public const VALID_GROUP = 'valid_group';

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
                    'prices' => [
                        ['value_net_amount' => '12,34', 'value_gross_amount' => '12,34'],
                        ['value_net_amount' => '12,34', 'value_gross_amount' => '12,34'],
                    ],
                ],
                [
                    'value_translation_key' => 'option_value_2_translation_key',
                    'value_sku' => 'option_value_2_sku_',
                    'prices' => [
                        ['value_net_amount' => 12.34, 'value_gross_amount' => 12.34],
                        ['value_net_amount' => 12.34, 'value_gross_amount' => 12.34],
                    ],
                ],
            ],
        ],
    ];
}
