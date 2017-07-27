<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\SortByQueryFilterPreProcessor;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Api
 * @group Business
 * @group Model
 * @group Processor
 * @group Pre
 * @group Filter
 * @group Query
 * @group SortByQueryFilterPreProcessorTest
 */
class SortByQueryFilterPreProcessorTest extends Unit
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
    public function testProcessEmpty()
    {
        $processor = new SortByQueryFilterPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame([], $apiRequestTransferAfter->getFilter()->getSort());
    }

    /**
     * @return void
     */
    public function testProcess()
    {
        $processor = new SortByQueryFilterPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());
        $apiRequestTransfer->setQueryData([
            SortByQueryFilterPreProcessor::SORT => 'foo,-bar',
        ]);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $expected = [
            'foo' => '',
            'bar' => '-',
        ];
        $this->assertSame($expected, $apiRequestTransferAfter->getFilter()->getSort());
    }

}
