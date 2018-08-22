<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnitStorage\Communication\Plugin\Synchronization;

use Codeception\Test\Unit;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Synchronization\ProductPackagingUnitSynchronizationDataPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnitStorage
 * @group Communication
 * @group Plugin
 * @group Synchronization
 * @group ProductPackagingUnitSynchronizationDataPluginTest
 * Add your own group annotations below this line
 */
class ProductPackagingUnitSynchronizationDataPluginTest extends Unit
{
    protected const TEST_INVALID_ID = 111;

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetDataWithIds(): void
    {
        $productPackagingUnitSynchronizationDataPlugin = $this->getProductPackagingUnitSynchronizationDataPlugin();
        $synchronizationDataTransfers = $productPackagingUnitSynchronizationDataPlugin->getData([
            static::TEST_INVALID_ID,
        ]);

        $this->assertEmpty($synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetDataWithoutIds(): void
    {
        $productPackagingUnitSynchronizationDataPlugin = $this->getProductPackagingUnitSynchronizationDataPlugin();
        $synchronizationDataTransfers = $productPackagingUnitSynchronizationDataPlugin->getData();

        $this->assertNotEmpty($synchronizationDataTransfers);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Synchronization\ProductPackagingUnitSynchronizationDataPlugin
     */
    protected function getProductPackagingUnitSynchronizationDataPlugin(): ProductPackagingUnitSynchronizationDataPlugin
    {
        return new ProductPackagingUnitSynchronizationDataPlugin();
    }
}
