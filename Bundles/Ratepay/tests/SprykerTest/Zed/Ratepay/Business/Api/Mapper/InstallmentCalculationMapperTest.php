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
 * @group InstallmentCalculationMapperTest
 * Add your own group annotations below this line
 */
class InstallmentCalculationMapperTest extends AbstractMapperTest
{

    /**
     * @return void
     */
    public function testMapper()
    {
        $this->mapperFactory
            ->getQuoteHeadMapper(
                $this->mockQuoteTransfer(),
                $this->mockRatepayPaymentInstallmentTransfer()
            )
            ->map();

        $this->mapperFactory
            ->getInstallmentCalculationMapper(
                $this->mockQuoteTransfer(),
                $this->mockRatepayPaymentInstallmentTransfer()
            )
            ->map();

        $this->assertEquals('calculation-by-rate', $this->requestTransfer->getInstallmentCalculation()->getSubType());
        $this->assertEquals(18, $this->requestTransfer->getInstallmentCalculation()->getAmount());
        $this->assertEquals(14, $this->requestTransfer->getInstallmentCalculation()->getCalculationRate());
        $this->assertEquals(3, $this->requestTransfer->getInstallmentCalculation()->getMonth());
        $this->assertEquals(28, $this->requestTransfer->getInstallmentCalculation()->getPaymentFirstday());
        $this->assertEquals('2016-05-15', $this->requestTransfer->getInstallmentCalculation()->getCalculationStart());
    }

}
