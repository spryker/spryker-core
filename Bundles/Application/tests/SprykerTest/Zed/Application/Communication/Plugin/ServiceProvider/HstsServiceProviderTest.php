<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\ZedHstsServiceProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Spryker
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
     * @return void
     */
    public function testDisabledHstsServiceProviderMustNotReturnHeader()
    {
        $eventMock = $this->getMockBuilder(FilterResponseEvent::class)->setMethods(['isMasterRequest', 'getResponse'])->disableOriginalConstructor()->getMock();
        $hstsMock = $this->getMockBuilder(ZedHstsServiceProvider::class)->setMethods(['getIsHstsEnabled', 'getHstsConfig'])->getMock();

        $eventMock->expects($this->once())
            ->method('isMasterRequest')
            ->willReturn(true);

        $eventMock->expects($this->never())
            ->method('getResponse');

        $hstsMock->expects($this->once())
            ->method('getIsHstsEnabled')
            ->willReturn(false);

        $hstsMock->onKernelResponse($eventMock);
    }

    /**
     * @return void
     */
    public function testHstsServiceProviderGeneratesHeader()
    {
        $eventMock = $this->getMockBuilder(FilterResponseEvent::class)->setMethods(['isMasterRequest', 'getResponse'])->disableOriginalConstructor()->getMock();
        $hstsMock = $this->getMockBuilder(ZedHstsServiceProvider::class)->setMethods(['getIsHstsEnabled', 'getHstsConfig'])->getMock();
        $responseMock = $this->getMockBuilder(Response::class)->getMock();
        $headersMock = $this->getMockBuilder(ResponseHeaderBag::class)->setMethods(['set'])->getMock();

        $responseMock->headers = $headersMock;

        $hstsConfig = [
            'max_age' => 31536000,
            'include_sub_domains' => true,
            'preload' => true,
        ];
        $hstsString = 'max-age=31536000; includeSubDomains; preload';

        $eventMock->expects($this->once())
            ->method('isMasterRequest')
            ->willReturn(true);

        $eventMock->expects($this->once())
            ->method('getResponse')
            ->willReturn($responseMock);

        $headersMock->expects($this->once())
            ->method('set')
            ->with(ZedHstsServiceProvider::HEADER_HSTS, $hstsString);

        $hstsMock->expects($this->once())
            ->method('getIsHstsEnabled')
            ->willReturn(true);

        $hstsMock->expects($this->once())
            ->method('getHstsConfig')
            ->willReturn($hstsConfig);

        $hstsMock->onKernelResponse($eventMock);
    }
}
