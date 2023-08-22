<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CartNotesBackendApi\Plugin\SalesOrdersBackendApi;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Glue\CartNotesBackendApi\Plugin\SalesOrdersBackendApi\CartNoteOrdersBackendApiAttributesMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CartNotesBackendApi
 * @group Plugin
 * @group SalesOrdersBackendApi
 * @group CartNoteOrdersBackendApiAttributesMapperPluginTest
 * Add your own group annotations below this line
 */
class CartNoteOrdersBackendApiAttributesMapperPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_ORDER_REFERENCE = 'TEST_ORDER_REFERENCE';

    /**
     * @var string
     */
    protected const TEST_ORDER_REFERENCE_2 = 'TEST_ORDER_REFERENCE_2';

    /**
     * @var string
     */
    protected const TEST_CART_NOTE = 'TEST_CART_NOTE';

    /**
     * @var string
     */
    protected const TEST_CART_NOTE_2 = 'TEST_CART_NOTE_2';

    /**
     * @dataProvider getMapOrderTransfersToOrdersBackendApiAttributesTransferDataProvider
     *
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     * @param list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer> $ordersBackendApiAttributesTransfers
     * @param list<array<string, mixed>> $expectedOrdersBackendApiAttributes
     *
     * @return void
     */
    public function testMapOrderTransfersToOrdersBackendApiAttributesTransfer(
        array $orderTransfers,
        array $ordersBackendApiAttributesTransfers,
        array $expectedOrdersBackendApiAttributes
    ): void {
        // Act
        $resultOrdersBackendApiAttributesTransfers = (new CartNoteOrdersBackendApiAttributesMapperPlugin())->mapOrderTransfersToOrdersBackendApiAttributesTransfers(
            $orderTransfers,
            $ordersBackendApiAttributesTransfers,
        );

        // Assert
        $resultOrdersBackendApiAttributes = array_map(function (OrdersBackendApiAttributesTransfer $ordersBackendApiAttributesTransfer) {
            return $ordersBackendApiAttributesTransfer->toArray();
        }, $resultOrdersBackendApiAttributesTransfers);

        $this->assertSame($expectedOrdersBackendApiAttributes, $resultOrdersBackendApiAttributes);
    }

    /**
     * @return array
     */
    protected function getMapOrderTransfersToOrdersBackendApiAttributesTransferDataProvider(): array
    {
        return [
            'Should not map cart note when oder for api order attributes is not provided.' => [
                [
                    (new OrderTransfer())->setCartNote(static::TEST_CART_NOTE),
                ],
                [
                    (new OrdersBackendApiAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE),
                ],
                [
                    (new OrdersBackendApiAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE)->toArray(),
                ],
            ],
            'Should not map cart note when api order attributes does not match order reference.' => [
                [
                    (new OrderTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE)->setCartNote(static::TEST_CART_NOTE),
                ],
                [
                    (new OrdersBackendApiAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE_2),
                    (new OrdersBackendApiAttributesTransfer()),
                ],
                [
                    (new OrdersBackendApiAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE_2)->toArray(),
                    (new OrdersBackendApiAttributesTransfer())->toArray(),
                ],
            ],
            'Should map cart note.' => [
                [
                    (new OrderTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE)->setCartNote(static::TEST_CART_NOTE),
                    (new OrderTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE_2)->setCartNote(static::TEST_CART_NOTE_2),
                ],
                [
                    (new OrdersBackendApiAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE),
                    (new OrdersBackendApiAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE_2),
                ],
                [
                    (new OrdersBackendApiAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE)->setCartNote(static::TEST_CART_NOTE)->toArray(),
                    (new OrdersBackendApiAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE_2)->setCartNote(static::TEST_CART_NOTE_2)->toArray(),
                ],
            ],
        ];
    }
}
