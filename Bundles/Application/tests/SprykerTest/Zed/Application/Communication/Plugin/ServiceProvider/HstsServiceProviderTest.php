<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Application\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\ZedHstsServiceProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Application
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group HstsServiceProviderTest
 * Add your own group annotations below this line
 */
class HstsServiceProviderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Application\ApplicationCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDisabledHstsServiceProviderMustNotReturnHeader(): void
    {
        $responseEvent = $this->tester->createResponseEvent();
        $hstsMock = $this->getMockBuilder(ZedHstsServiceProvider::class)->setMethods(['getIsHstsEnabled', 'getHstsConfig'])->getMock();

        $hstsMock->expects($this->once())
            ->method('getIsHstsEnabled')
            ->willReturn(false);

        $hstsMock->onKernelResponse($responseEvent);
    }

    /**
     * @return void
     */
    public function testHstsServiceProviderGeneratesHeader(): void
    {
        $responseMock = $this->getMockBuilder(Response::class)->getMock();
        $responseEvent = $this->tester->createResponseEvent(null, null, $responseMock);
        $hstsMock = $this->getMockBuilder(ZedHstsServiceProvider::class)->setMethods(['getIsHstsEnabled', 'getHstsConfig'])->getMock();

        $headersMock = $this->getMockBuilder(ResponseHeaderBag::class)->setMethods(['set'])->getMock();

        $responseMock->headers = $headersMock;

        $hstsConfig = [
            'max_age' => 31536000,
            'include_sub_domains' => true,
            'preload' => true,
        ];
        $hstsString = 'max-age=31536000; includeSubDomains; preload';

        $headersMock->expects($this->once())
            ->method('set')
            ->with(ZedHstsServiceProvider::HEADER_HSTS, $hstsString);

        $hstsMock->expects($this->once())
            ->method('getIsHstsEnabled')
            ->willReturn(true);

        $hstsMock->expects($this->once())
            ->method('getHstsConfig')
            ->willReturn($hstsConfig);

        $hstsMock->onKernelResponse($responseEvent);
    }
}
