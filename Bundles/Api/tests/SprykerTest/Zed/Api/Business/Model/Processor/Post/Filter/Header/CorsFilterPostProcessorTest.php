<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Business\Model\Processor\Post\Filter\Header;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Processor\Post\Filter\Header\CorsFilterPostProcessor;

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
 * @group Filter
 * @group Header
 * @group CorsFilterPostProcessorTest
 * Add your own group annotations below this line
 */
class CorsFilterPostProcessorTest extends Unit
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
            CorsFilterPostProcessor::HEADER_ACCESS_CONTROL_ALLOW_HEADERS => "origin, x-requested-with, accept",
            CorsFilterPostProcessor::HEADER_ACCESS_CONTROL_ALLOW_METHODS => "GET, PATCH, DELETE, OPTIONS",
            CorsFilterPostProcessor::HEADER_ACCESS_CONTROL_ALLOW_ORIGIN => "*",
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
            CorsFilterPostProcessor::HEADER_ACCESS_CONTROL_ALLOW_HEADERS => "origin, x-requested-with, accept",
            CorsFilterPostProcessor::HEADER_ACCESS_CONTROL_ALLOW_METHODS => "GET, POST, OPTIONS",
            CorsFilterPostProcessor::HEADER_ACCESS_CONTROL_ALLOW_ORIGIN => "*",
        ];
        $this->assertSame($expected, $apiResponseTransfer->getHeaders());
    }
}
