<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Mapper;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Api
 * @group Mapper
 * @group BankAccountMapperTest
 */
class BankAccountMapperTest extends AbstractMapperTest
{

    /**
     * @return void
     */
    public function testMapper()
    {
        $this->mapperFactory
            ->getBankAccountMapper(
                $this->mockRatepayPaymentRequestTransfer()
            )
            ->map();

        $this->assertEquals('iban', $this->requestTransfer->getBankAccount()->getIban());
        $this->assertEquals('bic', $this->requestTransfer->getBankAccount()->getBicSwift());
        $this->assertEquals('fn ln', $this->requestTransfer->getBankAccount()->getOwner());
    }

}
