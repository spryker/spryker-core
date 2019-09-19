<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Business\Model\Processor\Pre;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PathPreProcessor;

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
 * @group PathPreProcessorTest
 * Add your own group annotations below this line
 */
class PathPreProcessorTest extends Unit
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
    public function testProcess()
    {
        $processor = new PathPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setServerData([
            PathPreProcessor::SERVER_REQUEST_URI => '/api/rest/resource-name',
        ]);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('resource-name', $apiRequestTransferAfter->getPath());
    }

    /**
     * @return void
     */
    public function testProcessWithAdditionalParams()
    {
        $processor = new PathPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setServerData([
            PathPreProcessor::SERVER_REQUEST_URI => '/api/rest/resource-name/something/more',
        ]);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('resource-name/something/more', $apiRequestTransferAfter->getPath());
    }

    /**
     * @return void
     */
    public function testProcessWithQueryString()
    {
        $processor = new PathPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setServerData([
            PathPreProcessor::SERVER_REQUEST_URI => '/api/rest/resource-name?foo=bar',
        ]);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('resource-name', $apiRequestTransferAfter->getPath());
    }
}
