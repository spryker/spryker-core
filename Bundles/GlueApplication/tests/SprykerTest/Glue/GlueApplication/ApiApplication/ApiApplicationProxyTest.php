<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\ApiApplication;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationProxy;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAgnosticApiApplication;
use Spryker\Glue\GlueApplication\Exception\UnknownRequestFlowImplementationException;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface;
use Spryker\Shared\Application\ApplicationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group ApiApplication
 * @group ApiApplicationProxyTest
 * Add your own group annotations below this line
 */
class ApiApplicationProxyTest extends Unit
{
    /**
     * @return void
     */
    public function testBootIsExecutedOnBootBootstrapPlugin(): void
    {
        $applicationMock = $this->createMock(ApplicationInterface::class);
        $applicationMock
            ->expects($this->once())
            ->method('boot');

        $bootstrapPluginMock = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $bootstrapPluginMock
            ->expects($this->once())
            ->method('getApplication')
            ->willReturn($applicationMock);

        $apiApplicationProxy = new ApiApplicationProxy($bootstrapPluginMock);
        $apiApplicationProxy->boot();
    }

    /**
     * @return void
     */
    public function testRunIsExecutedOnRequestFlowAgnosticBootstrapPlugin(): void
    {
        $applicationMock = $this->createMock(RequestFlowAgnosticApiApplication::class);
        $applicationMock
            ->expects($this->once())
            ->method('run');

        $bootstrapPluginMock = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $bootstrapPluginMock
            ->expects($this->once())
            ->method('getApplication')
            ->willReturn($applicationMock);

        $apiApplicationProxy = new ApiApplicationProxy($bootstrapPluginMock);
        $apiApplicationProxy->run();
    }

    /**
     * @return void
     */
    public function testExceptionIsThrownIfAgnosticIsNotImplemented(): void
    {
        $bootstrapPluginMock = $this->createMock(GlueApplicationBootstrapPluginInterface::class);

        $this->expectException(UnknownRequestFlowImplementationException::class);

        $apiApplicationProxy = new ApiApplicationProxy($bootstrapPluginMock);
        $apiApplicationProxy->run();
    }
}
