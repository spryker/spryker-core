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
 * @group InstallmentPaymentMapperTest
 */
class InstallmentPaymentMapperTest extends AbstractMapperTest
{

    /**
     * @return void
     */
    public function testMapper()
    {
        $installment = $this->mockRatepayPaymentInstallmentTransfer();
        $quote = $this->mockQuoteTransfer();
        $quote->getPayment()
            ->setRatepayInstallment($installment);

        $ratepayPaymentRequestTransfer = $this->mockRatepayPaymentRequestTransfer($installment, $quote);

        $this->mapperFactory
            ->getBasketMapper(
                $ratepayPaymentRequestTransfer
            )
            ->map();

        $this->mapperFactory
            ->getInstallmentPaymentMapper(
                $ratepayPaymentRequestTransfer
            )
            ->map();

        $this->assertEquals('invoice', $this->requestTransfer->getInstallmentPayment()->getDebitPayType());
        $this->assertEquals('125.7', $this->requestTransfer->getInstallmentPayment()->getAmount());
    }

}
