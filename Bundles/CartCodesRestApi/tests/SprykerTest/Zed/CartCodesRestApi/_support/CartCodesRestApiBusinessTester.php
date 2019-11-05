<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartCodesRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Discount\DiscountConstants;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\CartCodesRestApi\Business\CartCodesRestApiFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CartCodesRestApiBusinessTester extends Actor
{
    use _generated\CartCodesRestApiBusinessTesterActions;

    public const TEST_QUOTE_UUID = 'test-quote-uuid';

    public const TEST_CUSTOMER_REFERENCE = 'DE--666';

    public const ITEMS = [
        [
            'sku' => 'test sku',
            'quantity' => '666',

        ],
    ];

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder(
            [
                'uuid' => static::TEST_QUOTE_UUID,
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
                'items' => static::ITEMS,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountVoucherTransfer
     */
    public function prepareDiscountVoucherTransfer(): DiscountVoucherTransfer
    {
        $override = [
            DiscountVoucherTransfer::MAX_NUMBER_OF_USES => 5,
            DiscountVoucherTransfer::CUSTOM_CODE => 'voucher',
            DiscountVoucherTransfer::QUANTITY => 3,
            DiscountVoucherTransfer::RANDOM_GENERATED_CODE_LENGTH => 3,
        ];

        $discountGeneralTransfer = $this->haveDiscount([
            DiscountGeneralTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_VOUCHER,
        ]);
        $override[DiscountVoucherTransfer::ID_DISCOUNT] = $discountGeneralTransfer->getIdDiscount();
        $discountVoucherTransfer = $this->haveGeneratedVoucherCodes($override);

        return $discountVoucherTransfer;
    }
}
