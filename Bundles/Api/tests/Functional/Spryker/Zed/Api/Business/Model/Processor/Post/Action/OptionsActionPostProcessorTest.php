<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Api\Business\Model\Processor\Post\Action;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Processor\Post\Action\OptionsActionPostProcessor;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Api
 * @group Business
 * @group Model
 * @group Processor
 * @group Post
 * @group Action
 * @group OptionsActionPostProcessorTest
 */
class OptionsActionPostProcessorTest extends Test
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

        $expected = [
            'Allow' => 'FOO, BAR',
        ];
        $this->assertSame($expected, $apiResponseTransfer->getHeaders());
    }

}
