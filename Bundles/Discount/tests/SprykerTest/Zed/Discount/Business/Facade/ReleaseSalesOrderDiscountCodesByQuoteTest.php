<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\Discount\DiscountBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Facade
 * @group ReleaseSalesOrderDiscountCodesByQuoteTest
 * Add your own group annotations below this line
 */
class ReleaseSalesOrderDiscountCodesByQuoteTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var string
     */
    protected const FIELD_NAME_FK_SALES_ORDER = 'fk_sales_order';

    /**
     * @var string
     */
    protected const FIELD_NAME_NAME = 'name';

    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected DiscountBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenIdSalesOrderIsNotSetInSaveOrderTransfer(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "idSalesOrder" of transfer `Generated\Shared\Transfer\SaveOrderTransfer` is null.');

        // Act
        $this->tester->getFacade()->releaseSalesOrderDiscountCodesByQuote(new QuoteTransfer(), new SaveOrderTransfer());
    }

    /**
     * @dataProvider releasesSalesOrderDiscountCodesDataProvider
     *
     * @param int $numberOfUses
     * @param int $maxNumberOfUses
     * @param int $expectedNumberOfUses
     * @param string|null $code
     *
     * @return void
     */
    public function testReleasesSalesOrderDiscountCodes(
        int $numberOfUses,
        int $maxNumberOfUses,
        int $expectedNumberOfUses,
        ?string $code = null
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $discountVoucherTransfer = $this->tester->createDiscountVoucher([
            DiscountVoucherTransfer::NUMBER_OF_USES => $numberOfUses,
            DiscountVoucherTransfer::MAX_NUMBER_OF_USES => $maxNumberOfUses,
        ]);
        $salesDiscountEntity = $this->tester->haveSalesDiscount([
            static::FIELD_NAME_FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            static::FIELD_NAME_NAME => 'test name',
        ]);
        $this->tester->haveSalesDiscountCode(
            $salesDiscountEntity->getIdSalesDiscount(),
            $code ?? $discountVoucherTransfer->getCodeOrFail(),
            '',
        );

        // Act
        $this->tester->getFacade()->releaseSalesOrderDiscountCodesByQuote(new QuoteTransfer(), $saveOrderTransfer);

        // Assert
        $updatedDiscountVoucherTransfer = $this->tester->findDiscountVoucher($discountVoucherTransfer->getCodeOrFail());

        $this->assertNotNull($updatedDiscountVoucherTransfer);
        $this->assertSame($expectedNumberOfUses, $updatedDiscountVoucherTransfer->getNumberOfUses());
    }

    /**
     * @return array<string, list<int|string>>
     */
    protected function releasesSalesOrderDiscountCodesDataProvider(): array
    {
        return [
            'Decreases number of uses for used voucher codes' => [2, 5, 1],
            'Does not decrease number of uses for not used voucher codes' => [0, 0, 0],
            'Does not decrease number of uses when no codes are found' => [2, 5, 2, 'anotherCode'],
        ];
    }
}
