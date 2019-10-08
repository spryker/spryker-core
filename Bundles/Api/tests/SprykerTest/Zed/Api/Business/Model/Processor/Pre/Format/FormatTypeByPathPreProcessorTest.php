<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Business\Model\Processor\Pre\Format;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Format\FormatTypeByPathPreProcessor;

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
 * @group Format
 * @group FormatTypeByPathPreProcessorTest
 * Add your own group annotations below this line
 */
class FormatTypeByPathPreProcessorTest extends Unit
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
        $processor = new FormatTypeByPathPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter(new ApiFilterTransfer());

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertNull($apiRequestTransferAfter->getFormatType());
    }

    /**
     * @return void
     */
    public function testProcessExtension()
    {
        $processor = new FormatTypeByPathPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setPath('resource-name/1.json');

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('json', $apiRequestTransferAfter->getFormatType());
        $this->assertSame('resource-name/1', $apiRequestTransfer->getPath());
    }
}
