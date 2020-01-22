<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\CmsSlotStorage\tests\SprykerTest\Zed\CmsSlotStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;

/**
 * Auto-generated group annotations
 *
 * @group Spryker
 * @group CmsSlotStorage
 * @group tests
 * @group SprykerTest
 * @group Zed
 * @group CmsSlotStorage
 * @group Business
 * @group Facade
 * @group CmsSlotStorageFacadeTest
 * Add your own group annotations below this line
 */
class CmsSlotStorageFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsSlotStorage\CmsSlotStorageBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetSynchronizationTransferCollectionReturnsCorrectNumberOfTransfers(): void
    {
        // Arrange
        $cmSlotStorageEntity1 = $this->tester->haveCmsSlotStorageInDb('key-1');
        $cmSlotStorageEntity2 = $this->tester->haveCmsSlotStorageInDb('key-2');
        $cmSlotStorageEntity3 = $this->tester->haveCmsSlotStorageInDb('key-3');

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()
            ->getSynchronizationTransferCollection(new FilterTransfer(), [
                $cmSlotStorageEntity1->getIdCmsSlotStorage(),
                $cmSlotStorageEntity2->getIdCmsSlotStorage(),
            ]);

        // Assert
        $this->assertCount(2, $synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetSynchronizationTransferCollectionReturnsTransferWithCorrectData(): void
    {
        // Arrange
        $data = [
            'property1' => 'value1',
            'property2' => 'value2',
        ];
        $cmSlotStorageEntity = $this->tester->haveCmsSlotStorageInDb('key-1', $data);

        // Act
        $synchronizationDataTransfer = $this->tester->getFacade()
            ->getSynchronizationTransferCollection(new FilterTransfer(), [$cmSlotStorageEntity->getIdCmsSlotStorage()])[0];

        // Assert
        $this->assertEquals($synchronizationDataTransfer->getData(), $data);
    }
}
