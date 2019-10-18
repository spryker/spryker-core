<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Business\Model\Processor\Pre\Resource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourceIdPreProcessor;

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
 * @group Resource
 * @group ResourceIdPreProcessorTest
 * Add your own group annotations below this line
 */
class ResourceIdPreProcessorTest extends Unit
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
    public function testProcessGet()
    {
        $processor = new ResourceIdPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('GET');
        $apiRequestTransfer->setPath('1');

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('1', $apiRequestTransferAfter->getResourceId());
    }

    /**
     * @return void
     */
    public function testProcessPost()
    {
        $processor = new ResourceIdPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('POST');
        $apiRequestTransfer->setPath('');

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertNull($apiRequestTransferAfter->getResourceId());
    }

    /**
     * @return void
     */
    public function testProcessPostWithAdditionalParameters()
    {
        $processor = new ResourceIdPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('GET');
        $apiRequestTransfer->setPath('1/foo/bar');

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('1', $apiRequestTransferAfter->getResourceId());
        $this->assertSame('foo/bar', $apiRequestTransferAfter->getPath());
    }
}
