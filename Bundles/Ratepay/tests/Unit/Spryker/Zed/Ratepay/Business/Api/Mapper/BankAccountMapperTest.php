<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

class BankAccountMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $this->mapperFactory
            ->getBankAccountMapper(
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer()
            )
            ->map();

        $this->assertEquals("iban", $this->requestTransfer->getBankAccount()->getIban());
        $this->assertEquals("bic", $this->requestTransfer->getBankAccount()->getBicSwift());
        $this->assertEquals("holder", $this->requestTransfer->getBankAccount()->getOwner());
    }

}
