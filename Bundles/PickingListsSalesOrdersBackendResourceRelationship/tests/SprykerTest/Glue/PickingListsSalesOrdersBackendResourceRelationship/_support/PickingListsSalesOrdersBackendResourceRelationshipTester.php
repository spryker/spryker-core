<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsSalesOrdersBackendResourceRelationship;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PickingListBuilder;
use Generated\Shared\DataBuilder\PickingListItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

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
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class PickingListsSalesOrdersBackendResourceRelationshipTester extends Actor
{
    use _generated\PickingListsSalesOrdersBackendResourceRelationshipTesterActions;

    /**
     * @var string
     */
    public const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @uses \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS
     *
     * @var string
     */
    protected const RESOURCE_PICKING_LIST_ITEMS = 'picking-list-items';

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

        return $this->havePickingList($pickingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemTransfer
     */
    public function createPickingListItemTransfer(
        ItemTransfer $itemTransfer
    ): PickingListItemTransfer {
        return (new PickingListItemBuilder([
            PickingListItemTransfer::ORDER_ITEM => $itemTransfer,
        ]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createSaveOrderTransferWithTwoItems(): SaveOrderTransfer
    {
        $stockData = $this->haveStock()->toArray();

        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $quoteTransfer->setItems(new ArrayObject([
            (new ItemBuilder([
                ItemTransfer::SKU => $this->haveProduct()->getSku(),
                ItemTransfer::WAREHOUSE => $stockData,
            ]))->build(),
            (new ItemBuilder([
                ItemTransfer::SKU => $this->haveProduct()->getSku(),
                ItemTransfer::WAREHOUSE => $stockData,
            ]))->build(),
        ]));

        return $this->haveOrderFromQuote(
            $quoteTransfer,
            static::DEFAULT_OMS_PROCESS_NAME,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return list<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    public function createGlueResourceTransfers(PickingListTransfer $pickingListTransfer): array
    {
        $glueResourceTransfers = [];
        foreach ($pickingListTransfer->getPickingListItems() as $pickingListItemTransfer) {
            $apiPickingListItemsAttributesTransfer = (new ApiPickingListItemsAttributesTransfer())->fromArray(
                $pickingListItemTransfer->toArray(),
                true,
            );

            $glueResourceTransfers[] = (new GlueResourceTransfer())
                ->setType(static::RESOURCE_PICKING_LIST_ITEMS)
                ->setAttributes($apiPickingListItemsAttributesTransfer);
        }

        return $glueResourceTransfers;
    }
}
