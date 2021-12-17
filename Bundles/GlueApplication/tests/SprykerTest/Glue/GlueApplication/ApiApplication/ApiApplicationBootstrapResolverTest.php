<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\ApiApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationBootstrapResolver;
use Spryker\Glue\GlueApplication\Exception\MissingApiApplicationException;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group ApiApplication
 * @group ApiApplicationBootstrapResolverTest
 * Add your own group annotations below this line
 */
class ApiApplicationBootstrapResolverTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueApplication\GlueApplicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testResolveWithNoApiApplicationBootstrapsWillThrowAnException(): void
    {
        $apiApplicationResolver = new ApiApplicationBootstrapResolver([], []);
        $this->expectException(MissingApiApplicationException::class);
        $apiApplicationResolver->resolveApiApplicationBootstrap((new GlueApiContextTransfer()));
    }

    /**
     * @return void
     */
    public function testInjectingBootstrapPluginNameWillBeOnlyBootstrapPluginsToBeConsideredInResolving(): void
    {
        $servingApiApplicationBootstrap = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $servingApiApplicationBootstrap
            ->expects($this->never())
            ->method('isServing');

        $nonServingApiApplicationBootstrap = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $nonServingApiApplicationBootstrap
            ->expects($this->never())
            ->method('isServing');

        $apiApplicationResolver = new ApiApplicationBootstrapResolver(
            [get_class($servingApiApplicationBootstrap)],
            [$servingApiApplicationBootstrap, $nonServingApiApplicationBootstrap],
        );

        $resolvedApiApplication = $apiApplicationResolver->resolveApiApplicationBootstrap((new GlueApiContextTransfer()));
    }

    /**
     * @return void
     */
    public function testResolveSkipsNonServingApiApplications(): void
    {
        $nonServingApiApplication = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $nonServingApiApplication
            ->expects($this->once())
            ->method('isServing')
            ->willReturn(false);

        $servingApiApplication = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $servingApiApplication
            ->expects($this->once())
            ->method('isServing')
            ->willReturn(true);

        $apiApplicationResolver = new ApiApplicationBootstrapResolver(
            [],
            [$nonServingApiApplication, $servingApiApplication],
        );

        $resolvedApiApplication = $apiApplicationResolver->resolveApiApplicationBootstrap((new GlueApiContextTransfer()));
        $this->assertSame($servingApiApplication, $resolvedApiApplication);
    }
}
