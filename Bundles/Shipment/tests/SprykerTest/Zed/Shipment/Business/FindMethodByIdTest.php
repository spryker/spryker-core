<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group FindMethodByIdTest
 * Add your own group annotations below this line
 */
class FindMethodByIdTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindMethodByIdShouldFindShipmentMethod(): void
    {
        // Arrange
         $shipmentMethodTransfer = $this->tester->haveShipmentMethod();

         // Act
         $resultTransfer = $this->tester->getFacade()->findMethodById($shipmentMethodTransfer->getIdShipmentMethod());

         // Assert
         $this->assertNotNull($resultTransfer, 'Result should not be null');
    }

    /**
     * @return void
     */
    public function testFindMethodByIdShouldNotReturnShipmentNethod(): void
    {
        // Arrange
        $this->tester->ensureShipmentMethodTableIsEmpty();

        // Act
        $shipmentMethodTransfer = $this->tester->getFacade()->findMethodById(100);

        // Assert
        $this->assertNull($shipmentMethodTransfer);
    }
}
