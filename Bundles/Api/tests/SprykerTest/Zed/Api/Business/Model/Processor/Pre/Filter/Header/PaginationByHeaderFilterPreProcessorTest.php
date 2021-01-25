<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Business\Model\Processor\Pre\Filter\Header;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Header\PaginationByHeaderFilterPreProcessor;

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
 * @group Header
 * @group PaginationByHeaderFilterPreProcessorTest
 * Add your own group annotations below this line
 */
class PaginationByHeaderFilterPreProcessorTest extends Unit
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testProcessWithDefaultsPageOne(): void
    {
        $config = new ApiConfig();
        $processor = new PaginationByHeaderFilterPreProcessor($config);

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());
        $apiRequestTransfer->setHeaderData([
            PaginationByHeaderFilterPreProcessor::RANGE => ['users=0-19'],
        ]);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);

        $this->assertSame(20, $apiRequestTransferAfter->getFilter()->getLimit());
        $this->assertSame(0, $apiRequestTransferAfter->getFilter()->getOffset());
    }

    /**
     * @return void
     */
    public function testProcessWithDefaultsPageTwo(): void
    {
        $config = new ApiConfig();
        $processor = new PaginationByHeaderFilterPreProcessor($config);

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());
        $apiRequestTransfer->setHeaderData([
            PaginationByHeaderFilterPreProcessor::RANGE => ['users=20-39'],
        ]);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame(20, $apiRequestTransferAfter->getFilter()->getLimit());
        $this->assertSame(20, $apiRequestTransferAfter->getFilter()->getOffset());
    }

    /**
     * @return void
     */
    public function testProcessWithCustomLimit(): void
    {
        $config = new ApiConfig();
        $processor = new PaginationByHeaderFilterPreProcessor($config);

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());
        $apiRequestTransfer->setHeaderData([
            PaginationByHeaderFilterPreProcessor::RANGE => ['users=20-29'],
        ]);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame(10, $apiRequestTransferAfter->getFilter()->getLimit());
        $this->assertSame(20, $apiRequestTransferAfter->getFilter()->getOffset());
    }

    /**
     * @return void
     */
    public function testProcessWithInvalidOffsetPagination(): void
    {
        $this->expectException('Spryker\Zed\Api\Business\Exception\ApiDispatchingException');
        $config = new ApiConfig();
        $processor = new PaginationByHeaderFilterPreProcessor($config);

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());
        $apiRequestTransfer->setHeaderData([
            PaginationByHeaderFilterPreProcessor::RANGE => ['users=1-20'],
        ]);

        $processor->process($apiRequestTransfer);
    }
}
