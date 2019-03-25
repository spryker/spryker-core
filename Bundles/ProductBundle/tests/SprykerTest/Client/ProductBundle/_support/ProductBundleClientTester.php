<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductBundle;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Inherited Methods
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
 * @SuppressWarnings(PHPMD)
 */
class ProductBundleClientTester extends Actor
{
    use _generated\ProductBundleClientTesterActions;

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function haveQuote(): QuoteTransfer
    {
        return (new QuoteBuilder())->build();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function haveCustomer(): CustomerTransfer
    {
        return (new CustomerBuilder())->build();
    }
}
