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
 * @group PaymentMapperTest
 */
class PaymentMapperTest extends AbstractMapperTest
{

    /**
     * @return void
     */
    public function testMapper()
    {
        $this->mapperFactory
            ->getPaymentMapper(
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer()
            )
            ->map();

        $this->assertEquals(99, $this->requestTransfer->getPayment()->getAmount());
        $this->assertEquals('iso3', $this->requestTransfer->getPayment()->getCurrency());
        $this->assertEquals('invoice', $this->requestTransfer->getPayment()->getMethod());
    }

}
