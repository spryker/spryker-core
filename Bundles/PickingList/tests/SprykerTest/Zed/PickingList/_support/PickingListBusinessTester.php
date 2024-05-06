<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingList;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\PickingListBuilder;
use Generated\Shared\DataBuilder\PickingListItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\PickingList\Persistence\SpyPickingListQuery;
use Propel\Runtime\Collection\ObjectCollection;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Zed\PickingList\PHPMD)
 *
 * @method \Spryker\Zed\PickingList\Business\PickingListFacadeInterface getFacade(?string $moduleName = null)
 */
class PickingListBusinessTester extends Actor
{
    use _generated\PickingListBusinessTesterActions;

    /**
     * @var string
     */
    public const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransfer(int $idSalesOrder): OrderTransfer
    {
        return $this->getLocator()
            ->sales()
            ->facade()
            ->findOrderByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderItemsWithWarehouse(
        OrderTransfer $orderTransfer,
        StockTransfer $stockTransfer
    ): OrderTransfer {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setWarehouse($stockTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createPersistedOrderTransferExpandedWithWarehouse(
        StockTransfer $stockTransfer
    ): OrderTransfer {
        $orderTransfer = $this->createPersistedOrderTransfer();
        $orderTransfer = $this->expandOrderItemsWithWarehouse($orderTransfer, $stockTransfer);

        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createPersistedOrderTransfer(): OrderTransfer
    {
        $saveOrderTransfer = $this->haveOrder(
            [],
            static::DEFAULT_OMS_PROCESS_NAME,
        );

        return $this->getOrderTransfer(
            $saveOrderTransfer->getIdSalesOrder(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createPersistedOrderTransferFromQuote(QuoteTransfer $quoteTransfer): OrderTransfer
    {
        $saveOrderTransfer = $this->haveOrderFromQuote(
            $quoteTransfer,
            static::DEFAULT_OMS_PROCESS_NAME,
        );

        return $this->getOrderTransfer(
            $saveOrderTransfer->getIdSalesOrder(),
        );
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithThreeItems(): QuoteTransfer
    {
        return (new QuoteBuilder())
            ->withStore()
            ->withItem()
            ->withItem()
            ->withItem()
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function createPickingListTransfer(array $seed = []): PickingListTransfer
    {
        $pickingListTransfer = (new PickingListBuilder($seed))
            ->withWarehouse()
            ->withUser()
            ->build();

        if (array_key_exists(PickingListTransfer::PICKING_LIST_ITEMS, $seed)) {
            $pickingListTransfer = $pickingListTransfer->setPickingListItems(
                new ArrayObject($seed[PickingListTransfer::PICKING_LIST_ITEMS]),
            );
        }

        return $pickingListTransfer;
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\PickingListItemTransfer
     */
    public function createPickingListItemTransfer(array $seed = []): PickingListItemTransfer
    {
        return (new PickingListItemBuilder($seed))->build();
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\PickingListItemTransfer
     */
    public function createPickingListItemTransferWithOrder(array $seed = []): PickingListItemTransfer
    {
        $this->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $saveOrderTransfer = $this->haveOrder(
            [],
            static::DEFAULT_OMS_PROCESS_NAME,
        );

        return $this->createPickingListItemTransfer($seed + [
            PickingListItemTransfer::ORDER_ITEM => $saveOrderTransfer->getOrderItems()->getIterator()->current(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PickingList\Persistence\SpyPickingList>
     */
    public function getPickingListsAssignedToUser(UserTransfer $userTransfer): ObjectCollection
    {
        return $this->getPickingListQuery()
            ->filterByUserUuid($userTransfer->getUuidOrFail())
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param bool $withUser
     *
     * @return void
     */
    public function createPickingListByOrder(OrderTransfer $orderTransfer, bool $withUser = true): void
    {
        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection */
        $itemTransferCollection = $orderTransfer->getItems();

        $pickingListItemTransfer = $this->createPickingListItemTransfer([
            PickingListItemTransfer::ORDER_ITEM => $itemTransferCollection->getIterator()->current(),
        ]);

        $pickingListTransfer = $this->createPickingListTransfer([
            PickingListTransfer::USER => $withUser ? $this->haveUser() : null,
            PickingListTransfer::WAREHOUSE => $this->haveStock(),
            PickingListTransfer::PICKING_LIST_ITEMS => [
                $pickingListItemTransfer,
            ],
        ]);
        $this->havePickingList($pickingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function createPickingListWithOnePickedAndOneNotPickedItems(OrderTransfer $orderTransfer): void
    {
        /** @var \ArrayObject<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection */
        $itemTransferCollection = $orderTransfer->getItems();

        $pickedPickingListItemTransfer = $this->createPickingListItemTransfer([
            PickingListItemTransfer::ORDER_ITEM => $itemTransferCollection->getIterator()->current(),
            PickingListItemTransfer::QUANTITY => 5,
            PickingListItemTransfer::NUMBER_OF_PICKED => 5,
        ]);

        $notPickedPickingListItemTransfer = $this->createPickingListItemTransfer([
            PickingListItemTransfer::ORDER_ITEM => $itemTransferCollection->getIterator()->current(),
            PickingListItemTransfer::QUANTITY => 3,
            PickingListItemTransfer::NUMBER_OF_NOT_PICKED => 3,
        ]);

        $this->createPickingListWithItems([$pickedPickingListItemTransfer, $notPickedPickingListItemTransfer]);
    }

    /**
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItems
     *
     * @return void
     */
    public function createPickingListWithItems(array $pickingListItems): void
    {
        $pickingListBusinessFactory = $this->mockFactoryMethod(
            'getCreatePickingListValidatorCompositeRules',
            [],
        );

        $pickingListFacade = $this->getFacade();
        $pickingListFacade->setFactory($pickingListBusinessFactory);

        $pickingListTransfer = $this->createPickingListTransfer([
            PickingListTransfer::USER => $this->haveUser(),
            PickingListTransfer::WAREHOUSE => $this->haveStock(),
            PickingListTransfer::PICKING_LIST_ITEMS => $pickingListItems,
        ]);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        $pickingListFacade->createPickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function ensurePickingListTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getPickingListQuery());
    }

    /**
     * @return \Orm\Zed\PickingList\Persistence\SpyPickingListQuery
     */
    protected function getPickingListQuery(): SpyPickingListQuery
    {
        return SpyPickingListQuery::create();
    }
}
