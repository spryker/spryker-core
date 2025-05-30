<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionOrderSaver;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionRepositoryInterface;
use SprykerTest\Zed\ProductOption\Business\MockProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group OptionGroup
 * @group ProductOptionOrderSaverTest
 * Add your own group annotations below this line
 */
class ProductOptionOrderSaverTest extends MockProvider
{
    /**
     * @return void
     */
    public function testSaveOptionShouldPersistProvidedOptions(): void
    {
        $glossaryFacadeMock = $this->createGlossaryFacadeMock();
        $glossaryFacadeMock->method('hasTranslation')->willReturn(true);
        $glossaryFacadeMock->method('translate')->willReturn('translated string');

        $productOptionOrderSaverMock = $this->createProductOptionOrderSaver($glossaryFacadeMock);

        $salesOrderItemOptionEntityMock = $this->createSalesOrderItemOptionMock();

        $salesOrderItemOptionEntityMock
            ->expects($this->exactly(2))
            ->method('save')
            ->willReturnCallback(function () use ($salesOrderItemOptionEntityMock): void {
                $salesOrderItemOptionEntityMock->setIdSalesOrderItemOption(1);
            });

        $productOptionOrderSaverMock->method('createSalesOrderItemOptionEntity')
            ->willReturn($salesOrderItemOptionEntityMock);

        $itemTransfer = new ItemTransfer();
        $productOptionTransfer = new ProductOptionTransfer();
        $itemTransfer->addProductOption($productOptionTransfer);

        $productOptionTransfer = new ProductOptionTransfer();
        $itemTransfer->addProductOption($productOptionTransfer);

        $checkoutResponeTransfer = new CheckoutResponseTransfer();

        $saveOrderTransfer = new SaveOrderTransfer();
        $saveOrderTransfer->addOrderItem($itemTransfer);

        $checkoutResponeTransfer->setSaveOrder($saveOrderTransfer);

        $productOptionOrderSaverMock->save(new QuoteTransfer(), $checkoutResponeTransfer);

        $orderItems = $checkoutResponeTransfer->getSaveOrder()->getOrderItems();

        $itemOptionTransfer = $orderItems[0]->getProductOptions()[0];

        $this->assertSame($itemOptionTransfer->getIdSalesOrderItemOption(), 1);
    }

    /**
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface|null $glossaryFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionOrderSaver
     */
    protected function createProductOptionOrderSaver(?ProductOptionToGlossaryFacadeInterface $glossaryFacadeMock = null): ProductOptionOrderSaver
    {
        if (!$glossaryFacadeMock) {
            $glossaryFacadeMock = $this->createGlossaryFacadeMock();
        }

        $productOptionRepositoryMock = $this->getMockBuilder(ProductOptionRepositoryInterface::class)->getMock();

        return $this->getMockBuilder(ProductOptionOrderSaver::class)
            ->setConstructorArgs([$glossaryFacadeMock, $productOptionRepositoryMock])
            ->onlyMethods(['createSalesOrderItemOptionEntity'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderItemOption
     */
    protected function createSalesOrderItemOptionMock(): SpySalesOrderItemOption
    {
        $mockedSalesOrderItemOption = $this->getMockBuilder(SpySalesOrderItemOption::class)
            ->onlyMethods(['save'])
            ->getMock();

        $mockedSalesOrderItemOption->method('save')
            ->willReturn(1);

        return $mockedSalesOrderItemOption;
    }
}
