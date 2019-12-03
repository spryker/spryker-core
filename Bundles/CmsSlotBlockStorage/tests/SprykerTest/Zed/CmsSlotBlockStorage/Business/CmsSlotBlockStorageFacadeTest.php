<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotBlockStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SpyCmsSlotBlockStorageEntityTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsSlotBlockStorage
 * @group Business
 * @group Facade
 * @group CmsSlotBlockStorageFacadeTest
 * Add your own group annotations below this line
 */
class CmsSlotBlockStorageFacadeTest extends Unit
{
    protected const FORMAT_SLOT_TEMPLATE_KEY = '%s:%s';

    /**
     * @var \SprykerTest\Zed\CmsSlotBlockStorage\CmsSlotBlockStorageBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetSynchronizationDataTransfersByCmsSlotBlockStorageIdsReturnsCorrectNumberOfTransfers(): void
    {
        // Assign
        $expectedCount = 5;
        $cmsSlotBlockStorageIds = [];
        $this->tester->ensureCmsSlotBlockStorageTableIsEmpty();

        for ($i = 0; $i < $expectedCount; $i++) {
            $seedData = [
                SpyCmsSlotBlockStorageEntityTransfer::SLOT_TEMPLATE_KEY => sprintf(
                    static::FORMAT_SLOT_TEMPLATE_KEY,
                    'template-path',
                    $i
                ),
            ];
            $cmsSlotBlockStorageIds[] = $this->tester->hasCmsSlotBlockStorage($seedData)->getIdCmsSlotBlockStorage();
        }

        // Act
        $synchronizationDataTransfers = $this->tester
            ->getFacade()
            ->getSynchronizationDataTransfersByCmsSlotBlockStorageIds(new FilterTransfer(), $cmsSlotBlockStorageIds);

        // Assert
        $this->assertCount($expectedCount, $synchronizationDataTransfers);
    }
}
