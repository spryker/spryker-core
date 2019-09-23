<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Business\Model\Processor\Pre\Filter\Query;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\CriteriaByQueryFilterPreProcessor;

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
 * @group CriteriaByQueryFilterPreProcessorTest
 * Add your own group annotations below this line
 */
class CriteriaByQueryFilterPreProcessorTest extends Unit
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
        $processor = new CriteriaByQueryFilterPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('{}', $apiRequestTransferAfter->getFilter()->getCriteriaJson());
    }

    /**
     * @return void
     */
    public function testProcess()
    {
        $processor = new CriteriaByQueryFilterPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());
        $apiRequestTransfer->setQueryData([
            CriteriaByQueryFilterPreProcessor::FILTER => '{foo: bar}',
        ]);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('{foo: bar}', $apiRequestTransferAfter->getFilter()->getCriteriaJson());
    }
}
