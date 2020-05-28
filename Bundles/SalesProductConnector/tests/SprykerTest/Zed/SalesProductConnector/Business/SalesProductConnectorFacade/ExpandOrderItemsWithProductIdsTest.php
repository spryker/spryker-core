<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConnector\Business\SalesProductConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConnector
 * @group Business
 * @group SalesProductConnectorFacade
 * @group ExpandOrderItemsWithProductIdsTest
 * Add your own group annotations below this line
 */
class ExpandOrderItemsWithProductIdsTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    protected const FAKE_SKU = 'FAKE_SKU';

    /**
     * @var \SprykerTest\Zed\SalesProductConnector\SalesProductConnectorBusinessTester
     */
    protected $tester;

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
    public function testExpandOrderItemsWithProductIds(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithProductIds($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertNotNull($itemTransfers[0]->getId());
        $this->assertNotNull($itemTransfers[0]->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithProductIdsWithoutSku(): void
    {
        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithProductIds([
                new ItemTransfer(),
                new ItemTransfer(),
                new ItemTransfer(),
            ]);

        // Assert
        $this->assertNull($itemTransfers[0]->getId());
        $this->assertNull($itemTransfers[1]->getId());
        $this->assertNull($itemTransfers[2]->getId());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithProductIdsWithFakeSku(): void
    {
        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithProductIds([
                (new ItemTransfer())->setSku(static::FAKE_SKU),
            ]);

        // Assert
        $this->assertNull($itemTransfers[0]->getId());
        $this->assertNull($itemTransfers[0]->getIdProductAbstract());
    }
}
