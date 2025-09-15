<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class DiscountConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    public const DEFAULT_VOUCHER_CODE_LENGTH = 6;

    /**
     * @var int
     */
    public const DEFAULT_MINIMUM_ITEM_AMOUNT = 1;

    /**
     * @var string
     */
    public const KEY_VOUCHER_CODE_CONSONANTS = 'consonants';

    /**
     * @var string
     */
    public const KEY_VOUCHER_CODE_VOWELS = 'vowels';

    /**
     * @var string
     */
    public const KEY_VOUCHER_CODE_NUMBERS = 'numbers';

    /**
     * @var string
     */
    protected const REDIRECT_URL_DEFAULT = '/discount/index/list';

    /**
     * @var int
     */
    protected const PRIORITY_MIN_VALUE = 1;

    /**
     * @var int
     */
    protected const PRIORITY_MAX_VALUE = 9999;

    /**
     * @var int
     */
    protected const VOUCHER_CODES_QUANTITY_MIN_VALUE = 1;

    /**
     * @var int
     */
    protected const VOUCHER_CODES_QUANTITY_MAX_VALUE = 14000;

    /**
     * @var bool
     */
    protected const IS_MONEY_COLLECTION_FORM_TYPE_PLUGIN_ENABLED = false;

    /**
     * @see https://en.wikipedia.org/wiki/Year_2038_problem
     *
     * @var string
     */
    protected const MAX_ALLOWED_DATETIME = '2038-01-19 03:14:07';

    /**
     * @api
     *
     * @return int
     */
    public function getVoucherCodeLength()
    {
        return static::DEFAULT_VOUCHER_CODE_LENGTH;
    }

    /**
     * @api
     *
     * @return array<string, array<mixed>>
     */
    public function getVoucherCodeCharacters()
    {
        return [
            static::KEY_VOUCHER_CODE_CONSONANTS => [
                'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z',
            ],
            static::KEY_VOUCHER_CODE_VOWELS => [
                'a', 'e', 'u',
            ],
            static::KEY_VOUCHER_CODE_NUMBERS => [
                1, 2, 3, 4, 5, 6, 7, 8, 9,
            ],
        ];
    }

    /**
     * @api
     *
     * @return int
     */
    public function getAllowedCodeCharactersLength()
    {
        /** @var int $charactersLength */
        $charactersLength = array_reduce($this->getVoucherCodeCharacters(), function ($length, $items) {
            $length += count($items);

            return $length;
        });

        return $charactersLength;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getVoucherPoolTemplateReplacementString()
    {
        return '[code]';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultRedirectUrl(): string
    {
        return static::REDIRECT_URL_DEFAULT;
    }

    /**
     * Specification:
     * - Defines the minimum numeric value for priority (the highest possible priority).
     *
     * @api
     *
     * @return int
     */
    public function getPriorityMinValue(): int
    {
        return static::PRIORITY_MIN_VALUE;
    }

    /**
     * Specification:
     * - Defines the maximum numeric value for priority (the lowest possible priority).
     *
     * @api
     *
     * @return int
     */
    public function getPriorityMaxValue(): int
    {
        return static::PRIORITY_MAX_VALUE;
    }

    /**
     * Specification:
     * - Defines the minimum quantity of generated voucher codes.
     *
     * @api
     *
     * @return int
     */
    public function getVoucherCodesQuantityMinValue(): int
    {
        return static::VOUCHER_CODES_QUANTITY_MIN_VALUE;
    }

    /**
     * Specification:
     * - Defines the maximum quantity of generated voucher codes.
     *
     * @api
     *
     * @return int
     */
    public function getVoucherCodesQuantityMaxValue(): int
    {
        return static::VOUCHER_CODES_QUANTITY_MAX_VALUE;
    }

    /**
     * Specification:
     * - Enables using money collection form type plugin.
     *
     * @api
     *
     * @return bool
     */
    public function isMoneyCollectionFormTypePluginEnabled(): bool
    {
        return static::IS_MONEY_COLLECTION_FORM_TYPE_PLUGIN_ENABLED;
    }

    /**
     * Specification:
     * - Defines the maximum datetime value that can be used for timestamps columns.
     *
     * @api
     *
     * @return string
     */
    public function getMaxAllowedDatetime(): string
    {
        return static::MAX_ALLOWED_DATETIME;
    }
}
