<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Api\Business\Model\Processor\Pre\Resource;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourceActionPreProcessor;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Api
 * @group Business
 * @group Model
 * @group Processor
 * @group Pre
 * @group Resource
 * @group ResourceParametersPreProcessorTest
 */
class ResourceParametersPreProcessorTest extends Test
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
    public function testProcessFind()
    {
        $processor = new ResourceActionPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('GET');
        $apiRequestTransfer->setPath('');

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('find', $apiRequestTransferAfter->getResourceAction());
    }

    /**
     * @return void
     */
    public function testProcessGet()
    {
        $processor = new ResourceActionPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('GET');
        $apiRequestTransfer->setPath('1');

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('get', $apiRequestTransferAfter->getResourceAction());
    }

    /**
     * @return void
     */
    public function testProcessAdd()
    {
        $processor = new ResourceActionPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('POST');
        $apiRequestTransfer->setPath('');

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('add', $apiRequestTransferAfter->getResourceAction());
    }

    /**
     * @return void
     */
    public function testProcessUpdate()
    {
        $processor = new ResourceActionPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('PATCH');
        $apiRequestTransfer->setPath('1');

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('update', $apiRequestTransferAfter->getResourceAction());
    }

    /**
     * @return void
     */
    public function testProcessRemove()
    {
        $processor = new ResourceActionPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setRequestType('DELETE');
        $apiRequestTransfer->setPath('1');

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('remove', $apiRequestTransferAfter->getResourceAction());
    }

}
