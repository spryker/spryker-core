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
 * @group BasketMapperTest
 * Add your own group annotations below this line
 */
class BasketMapperTest extends AbstractMapperTest
{

    /**
     * @return void
     */
    public function testMapper()
    {
        $this->mapperFactory
            ->getBasketMapper(
                $this->mockRatepayPaymentRequestTransfer()
            )
            ->map();

        $this->assertEquals(18, $this->requestTransfer->getShoppingBasket()->getAmount());
        $this->assertEquals('iso3', $this->requestTransfer->getShoppingBasket()->getCurrency());
        $this->assertEquals(0, $this->requestTransfer->getShoppingBasket()->getShippingUnitPrice());
    }

}
