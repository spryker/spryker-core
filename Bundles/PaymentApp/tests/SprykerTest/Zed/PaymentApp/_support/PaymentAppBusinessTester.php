<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentApp;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ExpressCheckoutPaymentRequestBuilder;
use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\Payment\PHPMD)
 *
 * @method \Spryker\Zed\PaymentApp\Business\PaymentAppFacadeInterface getFacade()
 */
class PaymentAppBusinessTester extends Actor
{
    use _generated\PaymentAppBusinessTesterActions;

    /**
     * @param array<string, mixed> $seedData
     * @param array<string, mixed> $quoteSeedData
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer
     */
    public function haveExpressCheckoutPaymentRequestTransfer(array $seedData = [], array $quoteSeedData = []): ExpressCheckoutPaymentRequestTransfer
    {
        return (new ExpressCheckoutPaymentRequestBuilder($seedData))
            ->withQuote($quoteSeedData)
            ->build();
    }
}
