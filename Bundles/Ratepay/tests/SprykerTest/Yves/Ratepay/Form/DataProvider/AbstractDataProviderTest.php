<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Ratepay\Form\DataProvider;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Ratepay
 * @group Form
 * @group DataProvider
 * @group AbstractDataProviderTest
 * Add your own group annotations below this line
 */
class AbstractDataProviderTest extends Unit
{
    public const PHONE_NUMBER = 1234567890;

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $billingAddress = new AddressTransfer();
        $billingAddress->setPhone(self::PHONE_NUMBER);
        $quoteTransfer->setBillingAddress($billingAddress);

        return $quoteTransfer;
    }
}
