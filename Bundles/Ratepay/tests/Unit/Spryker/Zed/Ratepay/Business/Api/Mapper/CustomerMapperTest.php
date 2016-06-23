<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

class CustomerMapperTest extends AbstractMapperTest
{

    public function testMapper()
    {
        $this->mapperFactory
            ->getCustomerMapper(
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer()
            )
            ->map();

        $this->assertEquals('yes', $this->requestTransfer->getCustomer()->getAllowCreditInquiry());
        $this->assertEquals('m', $this->requestTransfer->getCustomer()->getGender());
        $this->assertEquals('1980-01-02', $this->requestTransfer->getCustomer()->getDob());
        $this->assertEquals('127.1.2.3', $this->requestTransfer->getCustomer()->getIpAddress());
        $this->assertEquals('fn', $this->requestTransfer->getCustomer()->getFirstName());
        $this->assertEquals('ln', $this->requestTransfer->getCustomer()->getLastName());
        $this->assertEquals('email@site.com', $this->requestTransfer->getCustomer()->getEmail());
        $this->assertEquals('123456789', $this->requestTransfer->getCustomer()->getPhone());
    }

}
