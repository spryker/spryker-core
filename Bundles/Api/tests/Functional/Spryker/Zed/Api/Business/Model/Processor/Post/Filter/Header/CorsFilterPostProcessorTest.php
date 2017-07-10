<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Api\Business\Model\Processor\Post\Filter\Header;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Processor\Post\Filter\Header\CorsFilterPostProcessor;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Api
 * @group Business
 * @group Model
 * @group Processor
 * @group Post
 * @group Filter
 * @group Header
 * @group CorsFilterPostProcessorTest
 */
class CorsFilterPostProcessorTest extends Test
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
    public function testProcessWithItem()
    {
        $config = new ApiConfig();
        $processor = new CorsFilterPostProcessor($config);

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setResourceId(1);

        $apiResponseTransfer = new ApiResponseTransfer();

        $apiResponseTransfer = $processor->process($apiRequestTransfer, $apiResponseTransfer);
        $this->assertSame(['GET', 'PATCH', 'DELETE', 'OPTIONS'], $apiResponseTransfer->getOptions());

        $expected = [
            'Access-Control-Request-Headers' => "origin, x-requested-with, accept",
            'Access-Control-Request-Methods' => "GET, PATCH, DELETE, OPTIONS",
            'Access-Control-Allow-Origin' => "*",
        ];
        $this->assertSame($expected, $apiResponseTransfer->getHeaders());
    }

    /**
     * @return void
     */
    public function testProcessWithCollection()
    {
        $config = new ApiConfig();
        $processor = new CorsFilterPostProcessor($config);

        $apiRequestTransfer = new ApiRequestTransfer();

        $apiResponseTransfer = new ApiResponseTransfer();

        $apiResponseTransfer = $processor->process($apiRequestTransfer, $apiResponseTransfer);
        $this->assertSame(['GET', 'POST', 'OPTIONS'], $apiResponseTransfer->getOptions());

        $expected = [
            'Access-Control-Request-Headers' => "origin, x-requested-with, accept",
            'Access-Control-Request-Methods' => "GET, POST, OPTIONS",
            'Access-Control-Allow-Origin' => "*",
        ];
        $this->assertSame($expected, $apiResponseTransfer->getHeaders());
    }

}
