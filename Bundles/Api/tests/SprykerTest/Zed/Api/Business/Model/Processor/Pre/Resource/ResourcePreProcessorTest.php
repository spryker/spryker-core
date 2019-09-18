<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Business\Model\Processor\Pre\Resource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourcePreProcessor;

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
 * @group ResourcePreProcessorTest
 * Add your own group annotations below this line
 */
class ResourcePreProcessorTest extends Unit
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
        $processor = new ResourcePreProcessor();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setPath('resource-name/something/more');

        $apiRequestTransferAfter = $processor->process($apiRequestTransfer);
        $this->assertSame('resource-name', $apiRequestTransferAfter->getResource());
    }
}
