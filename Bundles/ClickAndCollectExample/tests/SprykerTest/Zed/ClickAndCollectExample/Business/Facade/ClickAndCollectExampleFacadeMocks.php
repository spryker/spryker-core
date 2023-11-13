<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ClickAndCollectExample\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\SellableItemResponseTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToAvailabilityFacadeBridge;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToAvailabilityFacadeInterface;
use SprykerTest\Zed\ClickAndCollectExample\ClickAndCollectExampleBusinessTester;

class ClickAndCollectExampleFacadeMocks extends Unit
{
    /**
     * @var \SprykerTest\Zed\ClickAndCollectExample\ClickAndCollectExampleBusinessTester
     */
    protected ClickAndCollectExampleBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->mockClickAndCollectExampleConfig();
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return void
     */
    protected function mockAvailabilityFacade(array $productOfferTransfers): void
    {
        $sellableItemsResponseTransfers = [];
        foreach ($productOfferTransfers as $productOfferTransfer) {
            $sellableItemsResponseTransfers[] = $this->createSellableItemsResponseTransfer($productOfferTransfer);
        }

        $this->tester->mockFactoryMethod('getAvailabilityFacade', function () use ($sellableItemsResponseTransfers) {
            return $this->getAvailabilityFacadeMock($sellableItemsResponseTransfers);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    protected function createSellableItemsResponseTransfer(ProductOfferTransfer $productOfferTransfer): SellableItemsResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer */
        $productOfferStockTransfer = $productOfferTransfer->getProductOfferStocks()->getIterator()->current();
        $isSellable = $productOfferStockTransfer->getIsNeverOutOfStock() || $productOfferStockTransfer->getQuantity()->toInt() > 0;
        $sellableItemResponseTransfer = (new SellableItemResponseTransfer())
            ->setIsSellable($isSellable)
            ->setAvailableQuantity($productOfferStockTransfer->getQuantity())
            ->setProductAvailabilityCriteria(
                (new ProductAvailabilityCriteriaTransfer())->setProductOfferReference($productOfferTransfer->getProductOfferReference()),
            );

        return (new SellableItemsResponseTransfer())->addSellableItemResponse($sellableItemResponseTransfer);
    }

    /**
     * @param list<\Generated\Shared\Transfer\SellableItemsResponseTransfer> $sellableItemsResponseTransfers
     *
     * @return \SprykerTest\Zed\ClickAndCollectExample\Business\Facade\MockObject|\Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToAvailabilityFacadeInterface
     */
    protected function getAvailabilityFacadeMock(
        array $sellableItemsResponseTransfers
    ): ClickAndCollectExampleToAvailabilityFacadeInterface {
        $availabilityFacadeMock = $this->getMockBuilder(AvailabilityFacadeInterface::class)->getMock();
        $availabilityFacadeMock->method('areProductsSellableForStore')->willReturnOnConsecutiveCalls(
            ...$sellableItemsResponseTransfers,
        );

        return new ClickAndCollectExampleToAvailabilityFacadeBridge($availabilityFacadeMock);
    }
}
