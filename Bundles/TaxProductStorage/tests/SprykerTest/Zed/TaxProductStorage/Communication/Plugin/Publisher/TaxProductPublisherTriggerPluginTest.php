<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductStorage\Communication\Plugin\Publisher;

use Codeception\Test\Unit;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\TaxProductStorage\Communication\Plugin\Publisher\TaxProductPublisherTriggerPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group TaxProductStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group TaxProductPublisherTriggerPluginTest
 * Add your own group annotations below this line
 */
class TaxProductPublisherTriggerPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\TaxProductStorage\TaxProductStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testTaxProductPublisherTriggerPluginShouldReturnDataWithOffsetAndLimit(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyProductAbstractQuery::create());
        $this->tester->haveProductAbstract();
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $this->tester->haveProductAbstract();

        // Act
        $productAbstractTransfers = (new TaxProductPublisherTriggerPlugin())->getData(1, 2);

        // Assert
        $this->assertCount(2, $productAbstractTransfers);
        $this->assertSame(
            $productAbstractTransfer1->getIdProductAbstract(),
            $productAbstractTransfers[0]->getIdProductAbstract(),
        );
        $this->assertSame(
            $productAbstractTransfer2->getIdProductAbstract(),
            $productAbstractTransfers[1]->getIdProductAbstract(),
        );
    }
}
