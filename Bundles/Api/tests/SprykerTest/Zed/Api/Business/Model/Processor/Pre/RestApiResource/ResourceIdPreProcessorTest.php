<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Business\Model\Processor\Pre\RestApiResource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\RestApiResource\ResourceIdPreProcessor;

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
 * @group RestApiResource
 * @group ResourceIdPreProcessorTest
 * Add your own group annotations below this line
 */
class ResourceIdPreProcessorTest extends Unit
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
    public function testProcessGet(): void
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
    public function testProcessPost(): void
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
    public function testProcessPostWithAdditionalParameters(): void
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
