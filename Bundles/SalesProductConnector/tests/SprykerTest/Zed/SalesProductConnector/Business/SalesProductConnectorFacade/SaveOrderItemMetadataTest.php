<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConnector\Business\SalesProductConnectorFacade;

use Codeception\Test\Unit;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadataQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConnector
 * @group Business
 * @group SalesProductConnectorFacade
 * @group SaveOrderItemMetadataTest
 * Add your own group annotations below this line
 */
class SaveOrderItemMetadataTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var string
     */
    protected const CORRECT_SUPER_ATTRIBUTES = '[]';

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
    public function testSaveOrderItemMetadataCorrectSuperAttributes(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuote();
        $saveOrderTransfer = $this->tester->createSaveOrder($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $this->tester->getFacade()->saveOrderItemMetadata($quoteTransfer, $saveOrderTransfer);

        // Assert
        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $salesOrderItemMetadataEntity = SpySalesOrderItemMetadataQuery::create()
                ->filterByFkSalesOrderItem($itemTransfer->getIdSalesOrderItem())
                ->findOne();
            $this->assertEquals(static::CORRECT_SUPER_ATTRIBUTES, $salesOrderItemMetadataEntity->getSuperAttributes());
        }
    }
}
