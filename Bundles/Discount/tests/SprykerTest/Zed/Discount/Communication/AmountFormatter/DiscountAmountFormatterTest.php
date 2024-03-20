<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Communication\AmountFormatter;

use Codeception\Test\Unit;
use Spryker\Zed\Discount\Communication\AmountFormatter\DiscountAmountFormatter;
use Spryker\Zed\Discount\Communication\DiscountCommunicationFactory;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface;
use SprykerTest\Zed\Discount\DiscountCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Communication
 * @group AmountFormatter
 * @group DiscountAmountFormatterTest
 * Add your own group annotations below this line
 */
class DiscountAmountFormatterTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Discount\DiscountCommunicationTester
     */
    protected DiscountCommunicationTester $tester;

    /**
     * @return void
     */
    public function testFormatFormatsAmount(): void
    {
        // Arrange
        $discountAmountFormatter = new DiscountAmountFormatter(
            (new DiscountCommunicationFactory())->getCalculatorPlugins(),
            $this->mockStoreFacade(),
        );

        $discountConfiguratorTransfer = $this->tester->haveDiscountConfiguratorTransfer();

        // Act
        $formattedAmount = $discountAmountFormatter->format($discountConfiguratorTransfer);

        // Assert
        $this->assertSame('€0.01', $formattedAmount->getDiscountCalculator()->getAmount());
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\AmountFormatter\DiscountAmountFormatter
     */
    protected function mockStoreFacade(): DiscountToStoreFacadeInterface
    {
        $storeFacadeMock = $this->getMockBuilder(DiscountToStoreFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $storeFacadeMock->method('isDynamicStoreEnabled')
            ->willReturn($this->tester->isDynamicStoreEnabled());

        return $storeFacadeMock;
    }
}
