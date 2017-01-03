<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Ratepay\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit_Framework_TestCase;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Ratepay
 * @group Form
 * @group DataProvider
 * @group AbstractDataProviderTest
 */
class AbstractDataProviderTest extends PHPUnit_Framework_TestCase
{

    const PHONE_NUMBER = 1234567890;

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
