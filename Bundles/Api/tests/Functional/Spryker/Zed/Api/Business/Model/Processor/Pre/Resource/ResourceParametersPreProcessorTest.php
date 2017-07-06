<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Api\Business\Model\Processor\Pre\Resource;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourceParametersPreProcessor;

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
    public function testProcessNoParameters()
    {
        $processor = new ResourceParametersPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setPath('');

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame([], $apiRequestTransferAfter->getResourceParameters());
    }

    /**
     * @return void
     */
    public function testProcessWithParameters()
    {
        $processor = new ResourceParametersPreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setPath('foo/bar');

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame(['foo', 'bar'], $apiRequestTransferAfter->getResourceParameters());
    }

}
