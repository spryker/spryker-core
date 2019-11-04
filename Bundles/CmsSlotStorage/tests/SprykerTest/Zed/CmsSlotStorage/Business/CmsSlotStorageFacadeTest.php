<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\CmsSlotStorage\tests\SprykerTest\Zed\CmsSlotStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsSlotCriteriaTransfer;
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
        $this->tester->haveCmsSlotStorageInDb(1, 'key-1');
        $this->tester->haveCmsSlotStorageInDb(2, 'key-2');
        $this->tester->haveCmsSlotStorageInDb(3, 'key-3');

        $cmsSlotCriteriaTransfer = (new CmsSlotCriteriaTransfer())->setFilter(new FilterTransfer());

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()->getSynchronizationTransferCollection($cmsSlotCriteriaTransfer, [1, 3]);

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
        $this->tester->haveCmsSlotStorageInDb(1, 'key-1', $data);

        $cmsSlotCriteriaTransfer = (new CmsSlotCriteriaTransfer())->setFilter(new FilterTransfer());

        // Act
        $synchronizationDataTransfer = $this->tester->getFacade()->getSynchronizationTransferCollection($cmsSlotCriteriaTransfer, [1])[0];

        // Assert
        $this->assertEquals($synchronizationDataTransfer->getData(), $data);
    }
}
