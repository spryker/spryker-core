<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Api\Mapper;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Api
 * @group Mapper
 * @group PaymentMapperTest
 * Add your own group annotations below this line
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
                $this->mockRatepayPaymentRequestTransfer()
            )
            ->map();

        $this->assertEquals(18, $this->requestTransfer->getPayment()->getAmount());
        $this->assertEquals('iso3', $this->requestTransfer->getPayment()->getCurrency());
        $this->assertEquals('invoice', $this->requestTransfer->getPayment()->getMethod());
    }

}
