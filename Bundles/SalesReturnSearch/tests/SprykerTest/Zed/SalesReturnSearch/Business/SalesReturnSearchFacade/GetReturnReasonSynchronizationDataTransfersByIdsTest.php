<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturnSearch\Business\SalesReturnSearchFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturnSearch
 * @group Business
 * @group SalesReturnSearchFacade
 * @group GetReturnReasonSynchronizationDataTransfersByIdsTest
 * Add your own group annotations below this line
 */
class GetReturnReasonSynchronizationDataTransfersByIdsTest extends Unit
{
    protected const RETURN_REASON_GLOSSARY_KEYS = [
        'return-reason-1',
        'return-reason-2',
        'return-reason-3',
    ];

    /**
     * @var \SprykerTest\Zed\SalesReturnSearch\SalesReturnSearchBusinessTester
     */
    protected $tester;

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
    public function testGetReturnReasonSynchronizationDataTransfersByIdsWorksWithIds(): void
    {
        // Arrange
        $returnReasonTransfers = $this->tester->haveReturnReasons(static::RETURN_REASON_GLOSSARY_KEYS);
        $returnReasonIds = $this->tester->extractReturnReasonIdsFromReturnReasonTransfers($returnReasonTransfers);
        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($returnReasonIds);
        $localeTransfers = $this->tester->getLocator()->locale()->facade()->getLocaleCollection();

        // Act
        $this->tester->getFacade()->writeCollectionByReturnReasonEvents($eventEntityTransfers);
        $salesReturnSearchEntities = $this->tester->getSalesReturnSearchEntitiesByReturnReasonIds($returnReasonIds);
        $synchronizationDataTransfers = $this->tester->getFacade()->getReturnReasonSynchronizationDataTransfersByIds(
            new FilterTransfer(),
            $returnReasonIds
        );

        // Assert
        $this->assertSame(
            count($salesReturnSearchEntities),
            count($synchronizationDataTransfers)
        );
    }

    /**
     * @return void
     */
    public function testGetReturnReasonSynchronizationDataTransfersByIdsWorksWithFilter(): void
    {
        // Arrange
        $returnReasonTransfers = $this->tester->haveReturnReasons(static::RETURN_REASON_GLOSSARY_KEYS);
        $returnReasonIds = $this->tester->extractReturnReasonIdsFromReturnReasonTransfers($returnReasonTransfers);
        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($returnReasonIds);

        // Act
        $this->tester->getFacade()->writeCollectionByReturnReasonEvents($eventEntityTransfers);
        $synchronizationDataTransfers = $this->tester->getFacade()->getReturnReasonSynchronizationDataTransfersByIds(
            (new FilterTransfer())->setOffset(1)->setLimit(1)
        );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testGetReturnReasonSynchronizationDataTransfersByIdsWorksWithFilterAndIds(): void
    {
        // Arrange
        $returnReasonTransfers = $this->tester->haveReturnReasons(static::RETURN_REASON_GLOSSARY_KEYS);
        $returnReasonIds = $this->tester->extractReturnReasonIdsFromReturnReasonTransfers($returnReasonTransfers);
        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($returnReasonIds);

        // Act
        $this->tester->getFacade()->writeCollectionByReturnReasonEvents($eventEntityTransfers);
        $synchronizationDataTransfers = $this->tester->getFacade()->getReturnReasonSynchronizationDataTransfersByIds(
            (new FilterTransfer())->setOffset(1)->setLimit(1),
            [$returnReasonIds[0]]
        );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
    }
}
