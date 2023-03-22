<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business\Builder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\PriceCartConnector\Business\Exception\TransferPropertyNotFoundException;
use SprykerTest\Zed\PriceCartConnector\PriceCartConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceCartConnector
 * @group Business
 * @group Builder
 * @group ItemIdentifierBuilderTest
 * Add your own group annotations below this line
 */
class ItemIdentifierBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SKU_1 = 'TEST_SKU_1';

    /**
     * @var string
     */
    protected const TEST_SKU_2 = 'TEST_SKU_2';

    /**
     * @var string
     */
    protected const INVALID_TRANSFER_PROPERTY = 'INVALID_TRANSFER_PROPERTY';

    /**
     * @var \SprykerTest\Zed\PriceCartConnector\PriceCartConnectorBusinessTester
     */
    protected PriceCartConnectorBusinessTester $tester;

    /**
     * @dataProvider getGetItemFieldsForIdentifierDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $secondItemTransfer
     * @param list<string> $itemFieldsForIdentifier
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testGetItemFieldsForIdentifier(
        ItemTransfer $itemTransfer,
        ItemTransfer $secondItemTransfer,
        array $itemFieldsForIdentifier,
        bool $expectedResult
    ): void {
        // Arrange
        $itemIdentifierBuilder = $this->tester->createItemIdentifierBuilder(
            $this->tester->createPriceCartConnectorConfigMock($itemFieldsForIdentifier),
        );

        // Act
        $itemIdentifier = $itemIdentifierBuilder->buildItemIdentifier($itemTransfer);
        $secondItemTransfer = $itemIdentifierBuilder->buildItemIdentifier($secondItemTransfer);

        // Assert
        $this->assertSame($expectedResult, $itemIdentifier === $secondItemTransfer);
    }

    /**
     * @return void
     */
    public function testGetItemFieldsForIdentifierShouldReturnEmptyIdentifierWhenFieldsAreNotSet(): void
    {
        // Arrange
        $itemIdentifierBuilderTest = $this->tester->createItemIdentifierBuilder(
            $this->tester->createPriceCartConnectorConfigMock(),
        );

        // Act
        $itemIdentifier = $itemIdentifierBuilderTest->buildItemIdentifier(new ItemTransfer());

        // Assert
        $this->assertEmpty($itemIdentifier);
    }

    /**
     * @return void
     */
    public function testGetItemFieldsForIdentifierShouldThrowExceptionWhenTransferPropertyNotFound(): void
    {
        // Assert
        $this->expectException(TransferPropertyNotFoundException::class);

        // Arrange
        $itemIdentifierBuilder = $this->tester->createItemIdentifierBuilder(
            $this->tester->createPriceCartConnectorConfigMock([static::INVALID_TRANSFER_PROPERTY]),
        );

        // Act
        $itemIdentifierBuilder->buildItemIdentifier(new ItemTransfer());
    }

    /**
     * @return array<string, array<list<string>|\Generated\Shared\Transfer\ItemTransfer|bool>>
     */
    protected function getGetItemFieldsForIdentifierDataProvider(): array
    {
        return [
            'Identifiers for items with the same quantities and SKUs should be equal.' => [
                (new ItemTransfer())->setSku(static::TEST_SKU_1)->setQuantity(1),
                (new ItemTransfer())->setSku(static::TEST_SKU_1)->setQuantity(1),
                [ItemTransfer::SKU, ItemTransfer::QUANTITY],
                true,
            ],
            'Identifiers for items with different quantities should not be equal.' => [
                (new ItemTransfer())->setSku(static::TEST_SKU_1)->setQuantity(1),
                (new ItemTransfer())->setSku(static::TEST_SKU_1)->setQuantity(2),
                [ItemTransfer::SKU, ItemTransfer::QUANTITY],
                false,
            ],
            'Identifiers for items with different SKUs should not be equal.' => [
                (new ItemTransfer())->setSku(static::TEST_SKU_1)->setQuantity(1),
                (new ItemTransfer())->setSku(static::TEST_SKU_2)->setQuantity(1),
                [ItemTransfer::SKU, ItemTransfer::QUANTITY],
                false,
            ],
        ];
    }
}
