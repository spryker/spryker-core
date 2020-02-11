<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Business\Model\Processor\Pre\RestApiResource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\RestApiResource\ResourceActionPreProcessor;

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
 * @group ResourceActionPreProcessorTest
 * Add your own group annotations below this line
 */
class ResourceActionPreProcessorTest extends Unit
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
        $processor = new ResourceActionPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('GET');
        $apiRequestTransfer->setResourceId(1);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('get', $apiRequestTransferAfter->getResourceAction());
    }

    /**
     * @return void
     */
    public function testProcessPost(): void
    {
        $processor = new ResourceActionPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('POST');
        $apiRequestTransfer->setResourceId(null);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('add', $apiRequestTransferAfter->getResourceAction());
    }

    /**
     * @return void
     */
    public function testProcessPostInvalid(): void
    {
        $this->expectException('Symfony\Component\HttpKernel\Exception\BadRequestHttpException');
        $processor = new ResourceActionPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('POST');
        $apiRequestTransfer->setResourceId(1);

        $processor->process($apiRequestTransfer);
    }

    /**
     * @return void
     */
    public function testProcessPatch(): void
    {
        $processor = new ResourceActionPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('PATCH');
        $apiRequestTransfer->setResourceId(1);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('update', $apiRequestTransferAfter->getResourceAction());
    }

    /**
     * @return void
     */
    public function testProcessDelete(): void
    {
        $processor = new ResourceActionPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('DELETE');
        $apiRequestTransfer->setResourceId(1);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('remove', $apiRequestTransferAfter->getResourceAction());
    }

    /**
     * @return void
     */
    public function testProcessFind(): void
    {
        $processor = new ResourceActionPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('GET');
        $apiRequestTransfer->setResourceId(null);

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('find', $apiRequestTransferAfter->getResourceAction());
    }
}
