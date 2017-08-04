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
 * @group CustomerMapperTest
 * Add your own group annotations below this line
 */
class CustomerMapperTest extends AbstractMapperTest
{

    /**
     * @return void
     */
    public function testMapper()
    {
        $this->mapperFactory
            ->getCustomerMapper(
                $this->mockRatepayPaymentRequestTransfer()
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
