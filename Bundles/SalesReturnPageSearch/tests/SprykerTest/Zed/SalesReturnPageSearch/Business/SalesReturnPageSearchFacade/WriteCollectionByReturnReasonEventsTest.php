<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturnPageSearch\Business\SalesReturnPageSearchFacade;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturnPageSearch
 * @group Business
 * @group SalesReturnPageSearchFacade
 * @group WriteCollectionByReturnReasonEventsTest
 * Add your own group annotations below this line
 */
class WriteCollectionByReturnReasonEventsTest extends Unit
{
    protected const RETURN_REASON_GLOSSARY_KEYS = [
        'return-reason-1',
        'return-reason-2',
        'return-reason-3',
    ];

    /**
     * @var \SprykerTest\Zed\SalesReturnPageSearch\SalesReturnPageSearchBusinessTester
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
    public function testWriteCollectionByReturnReasonEventsWritesRecords(): void
    {
        // Arrange
        $localeTransfers = $this->tester->getLocator()->locale()->facade()->getLocaleCollection();
        $returnReasonTransfers = $this->tester->haveReturnReasons(static::RETURN_REASON_GLOSSARY_KEYS);
        $returnReasonIds = $this->tester->extractReturnReasonIdsFromReturnReasonTransfers($returnReasonTransfers);
        $eventEntityTransfers = $this->tester->createEventEntityTransfersFromIds($returnReasonIds);

        // Act
        $this->tester->getFacade()->writeCollectionByReturnReasonEvents($eventEntityTransfers);
        $salesReturnPageSearchEntities = $this->tester->getSalesReturnPageSearchEntitiesByReturnReasonIds($returnReasonIds);

        // Assert
        $this->assertSame(
            count($returnReasonTransfers) * count($localeTransfers),
            $salesReturnPageSearchEntities->count()
        );
    }

    /**
     * @return void
     */
    public function testWriteCollectionByReturnReasonEventsDoesNothingForEmptyEventTransfers(): void
    {
        // Act
        $this->tester->getFacade()->writeCollectionByReturnReasonEvents([]);
        $salesReturnPageSearchEntities = $this->tester->getSalesReturnPageSearchEntitiesByReturnReasonIds();

        // Assert
        $this->assertCount(0, $salesReturnPageSearchEntities);
    }
}
