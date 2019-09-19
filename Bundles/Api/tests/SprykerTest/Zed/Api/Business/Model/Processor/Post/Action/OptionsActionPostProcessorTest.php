<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Business\Model\Processor\Post\Action;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Processor\Post\Action\OptionsActionPostProcessor;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Api
 * @group Business
 * @group Model
 * @group Processor
 * @group Post
 * @group Action
 * @group OptionsActionPostProcessorTest
 * Add your own group annotations below this line
 */
class OptionsActionPostProcessorTest extends Unit
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
    public function testProcessWithDefaultsPageOne()
    {
        $config = new ApiConfig();
        $processor = new OptionsActionPostProcessor($config);

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setResourceAction(ApiConfig::ACTION_OPTIONS);

        $apiResponseTransfer = new ApiResponseTransfer();
        $apiResponseTransfer->setOptions(['FOO', 'BAR']);

        $apiResponseTransfer = $processor->process($apiRequestTransfer, $apiResponseTransfer);
        $this->assertSame(ApiConfig::HTTP_CODE_SUCCESS, $apiResponseTransfer->getCode());
    }
}
