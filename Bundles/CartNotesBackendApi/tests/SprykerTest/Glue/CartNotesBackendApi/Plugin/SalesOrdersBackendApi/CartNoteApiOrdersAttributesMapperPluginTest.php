<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CartNotesBackendApi\Plugin\SalesOrdersBackendApi;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiOrdersAttributesTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Glue\CartNotesBackendApi\Plugin\SalesOrdersBackendApi\CartNoteApiOrdersAttributesMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CartNotesBackendApi
 * @group Plugin
 * @group SalesOrdersBackendApi
 * @group CartNoteApiOrdersAttributesMapperPluginTest
 * Add your own group annotations below this line
 */
class CartNoteApiOrdersAttributesMapperPluginTest extends Unit
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
     * @dataProvider getMapOrderTransfersToApiOrdersAttributesTransferDataProvider
     *
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     * @param list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer> $apiOrdersAttributesTransfers
     * @param list<array<string, mixed>> $expectedApiOrdersAttributes
     *
     * @return void
     */
    public function testMapOrderTransfersToApiOrdersAttributesTransfer(
        array $orderTransfers,
        array $apiOrdersAttributesTransfers,
        array $expectedApiOrdersAttributes
    ): void {
        // Act
        $resultApiOrdersAttributesTransfers = (new CartNoteApiOrdersAttributesMapperPlugin())->mapOrderTransfersToApiOrdersAttributesTransfer(
            $orderTransfers,
            $apiOrdersAttributesTransfers,
        );

        // Assert
        $resultApiOrdersAttributes = array_map(function (ApiOrdersAttributesTransfer $apiOrdersAttributesTransfer) {
            return $apiOrdersAttributesTransfer->toArray();
        }, $resultApiOrdersAttributesTransfers);

        $this->assertSame($expectedApiOrdersAttributes, $resultApiOrdersAttributes);
    }

    /**
     * @return array
     */
    protected function getMapOrderTransfersToApiOrdersAttributesTransferDataProvider(): array
    {
        return [
            'Should not map cart note when oder for api order attributes is not provided.' => [
                [
                    (new OrderTransfer())->setCartNote(static::TEST_CART_NOTE),
                ],
                [
                    (new ApiOrdersAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE),
                ],
                [
                    (new ApiOrdersAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE)->toArray(),
                ],
            ],
            'Should not map cart note when api order attributes does not match order reference.' => [
                [
                    (new OrderTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE)->setCartNote(static::TEST_CART_NOTE),
                ],
                [
                    (new ApiOrdersAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE_2),
                    (new ApiOrdersAttributesTransfer()),
                ],
                [
                    (new ApiOrdersAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE_2)->toArray(),
                    (new ApiOrdersAttributesTransfer())->toArray(),
                ],
            ],
            'Should map cart note.' => [
                [
                    (new OrderTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE)->setCartNote(static::TEST_CART_NOTE),
                    (new OrderTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE_2)->setCartNote(static::TEST_CART_NOTE_2),
                ],
                [
                    (new ApiOrdersAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE),
                    (new ApiOrdersAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE_2),
                ],
                [
                    (new ApiOrdersAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE)->setCartNote(static::TEST_CART_NOTE)->toArray(),
                    (new ApiOrdersAttributesTransfer())->setOrderReference(static::TEST_ORDER_REFERENCE_2)->setCartNote(static::TEST_CART_NOTE_2)->toArray(),
                ],
            ],
        ];
    }
}
