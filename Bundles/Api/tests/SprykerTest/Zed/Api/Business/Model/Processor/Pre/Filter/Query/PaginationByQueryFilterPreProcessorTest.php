<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Business\Model\Processor\Pre\Filter\Query;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\PaginationByQueryFilterPreProcessor;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Api
 * @group Business
 * @group Model
 * @group Processor
 * @group Pre
 * @group Filter
 * @group Query
 * @group PaginationByQueryFilterPreProcessorTest
 * Add your own group annotations below this line
 */
class PaginationByQueryFilterPreProcessorTest extends Unit
{
    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testProcessWithDefaults()
    {
        $config = new ApiConfig();
        $processor = new PaginationByQueryFilterPreProcessor($config);

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame(20, $apiRequestTransferAfter->getFilter()->getLimit());
        $this->assertSame(0, $apiRequestTransferAfter->getFilter()->getOffset());
    }

    /**
     * @return void
     */
    public function testProcessWithDefaultsPageTwo()
    {
        $config = new ApiConfig();
        $processor = new PaginationByQueryFilterPreProcessor($config);

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());
        $apiRequestTransfer->setQueryData([
            PaginationByQueryFilterPreProcessor::LIMIT => 20,
            PaginationByQueryFilterPreProcessor::PAGE => 2,
        ]);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame(20, $apiRequestTransferAfter->getFilter()->getLimit());
        $this->assertSame(20, $apiRequestTransferAfter->getFilter()->getOffset());
    }

    /**
     * @return void
     */
    public function testProcessWithCustomLimit()
    {
        $config = new ApiConfig();
        $processor = new PaginationByQueryFilterPreProcessor($config);

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());
        $apiRequestTransfer->setQueryData([
            PaginationByQueryFilterPreProcessor::LIMIT => 10,
            PaginationByQueryFilterPreProcessor::PAGE => 5,
        ]);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame(10, $apiRequestTransferAfter->getFilter()->getLimit());
        $this->assertSame(40, $apiRequestTransferAfter->getFilter()->getOffset());
    }

    /**
     * @return void
     */
    public function testProcessWithTooHighLimit()
    {
        $config = new ApiConfig();
        $processor = new PaginationByQueryFilterPreProcessor($config);

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());
        $apiRequestTransfer->setQueryData([
            PaginationByQueryFilterPreProcessor::LIMIT => 999,
            PaginationByQueryFilterPreProcessor::PAGE => 3,
        ]);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame(100, $apiRequestTransferAfter->getFilter()->getLimit());
        $this->assertSame(200, $apiRequestTransferAfter->getFilter()->getOffset());
    }
}
