<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartCode;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
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
 * @method \Spryker\Zed\CartCode\Business\CartCodeFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CartCodeBusinessTester extends Actor
{
    use _generated\CartCodeBusinessTesterActions;

    public const TEST_QUOTE_UUID = 'test-quote-uuid';

    public const TEST_CUSTOMER_REFERENCE = 'DE--666';

    public const ITEMS = [
        [
            'sku' => 'test sku',
            'quantity' => '666',
        ],
    ];

    /**
     * @param bool $isLocked
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransfer(bool $isLocked): QuoteTransfer
    {
        return (new QuoteBuilder(
            [
                'uuid' => static::TEST_QUOTE_UUID,
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
                'customer' => (new CustomerTransfer())->setCustomerReference(static::TEST_CUSTOMER_REFERENCE),
                'items' => static::ITEMS,
                'isLocked' => $isLocked,
            ]
        ))->build();
    }

    /**
     * @param bool $isLocked
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransferWithDiscount(bool $isLocked, string $code): QuoteTransfer
    {
        return (new QuoteBuilder(
            [
                'uuid' => static::TEST_QUOTE_UUID,
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
                'customer' => (new CustomerTransfer())->setCustomerReference(static::TEST_CUSTOMER_REFERENCE),
                'items' => static::ITEMS,
                'voucherDiscounts' => [
                    [
                        'voucherCode' => $code,
                    ],
                ],
                'isLocked' => $isLocked,
            ]
        ))->build();
    }
}
