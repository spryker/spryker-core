<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch\Business\ServicePointSearchFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;
use SprykerTest\Zed\ServicePointSearch\ServicePointSearchBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointSearch
 * @group Business
 * @group ServicePointSearchFacade
 * @group GetServicePointSynchronizationDataTransfersByIdsTest
 * Add your own group annotations below this line
 */
class GetServicePointSynchronizationDataTransfersByIdsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ServicePointSearch\ServicePointSearchBusinessTester
     */
    protected ServicePointSearchBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependencies();
        $this->tester->cleanUpDatabase();
    }

    /**
     * @return void
     */
    public function testGetServicePointSynchronizationDataTransfersByIdsReturnsSynchronizationTransfers(): void
    {
        // Arrange
        $servicePointIds = $this->tester->createTwoServicePointsForTwoStores();

        // Act
        $synchronizationDataTransfers = $this->tester
            ->getFacade()
            ->getServicePointSynchronizationDataTransfersByIds(new FilterTransfer(), $servicePointIds);

        // Assert
        $this->assertCount(4, $synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetServicePointSynchronizationDataTransfersByIdsReturnsSynchronizationTransferLimitedByProvidedIds(): void
    {
        // Arrange
        $servicePointIds = $this->tester->createTwoServicePointsForTwoStores();

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()->getServicePointSynchronizationDataTransfersByIds(
            new FilterTransfer(),
            [$servicePointIds[0]],
        );

        // Assert
        $this->assertCount(2, $synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetServicePointSynchronizationDataTransfersByIdsReturnsSynchronizationTransferLimitedByFilterLimit(): void
    {
        // Arrange
        $this->tester->createTwoServicePointsForTwoStores();

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()->getServicePointSynchronizationDataTransfersByIds(
            (new FilterTransfer())->setOffset(1)->setLimit(1),
        );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetServicePointSynchronizationDataTransfersByIdsReturnsSynchronizationTransferLimitedByFilterLimitAndIds(): void
    {
        // Arrange
        $servicePointIds = $this->tester->createTwoServicePointsForTwoStores();

        // Act
        $synchronizationDataTransfers = $this->tester->getFacade()->getServicePointSynchronizationDataTransfersByIds(
            (new FilterTransfer())->setOffset(1)->setLimit(1),
            [$servicePointIds[0]],
        );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
    }
}
