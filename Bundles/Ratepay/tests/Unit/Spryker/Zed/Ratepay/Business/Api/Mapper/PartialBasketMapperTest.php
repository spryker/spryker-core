<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\ItemTransfer;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Api
 * @group Mapper
 * @group PartialBasketMapperTest
 */
class PartialBasketMapperTest extends AbstractMapperTest
{

    /**
     * @return void
     */
    public function testMapper()
    {
        $this->mapperFactory
            ->getPartialBasketMapper(
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer(),
                [
                    (new ItemTransfer())->setSumGrossPriceWithProductOptionAndDiscountAmounts(9900),
                    (new ItemTransfer())->setSumGrossPriceWithProductOptionAndDiscountAmounts(1500),
                    (new ItemTransfer())->setSumGrossPriceWithProductOptionAndDiscountAmounts(100),
                ]
            )
            ->map();

        $this->assertEquals(0, $this->requestTransfer->getShoppingBasket()->getAmount());
        $this->assertEquals('iso3', $this->requestTransfer->getShoppingBasket()->getCurrency());
    }

}
