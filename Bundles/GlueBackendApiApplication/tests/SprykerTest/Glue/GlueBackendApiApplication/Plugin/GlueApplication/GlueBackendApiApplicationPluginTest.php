<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\BackendApiGlueApplicationBootstrapPlugin;
use Spryker\Shared\Application\ApplicationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplication
 * @group Plugin
 * @group GlueApplication
 * @group GlueBackendApiApplicationPluginTest
 * Add your own group annotations below this line
 */
class GlueBackendApiApplicationPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testIsNotServingEmptyPathRegex(): void
    {
        $glueBackendApiApplication = new BackendApiGlueApplicationBootstrapPlugin();

        $this->assertFalse($glueBackendApiApplication->isServing(new GlueApiContextTransfer()));
    }

    /**
     * @return void
     */
    public function testRunWillCreateApplication(): void
    {
        $applicationMock = $this->createMock(ApplicationInterface::class);

        $factoryMock = $this->createMock(GlueBackendApiApplicationFactory::class);
        $factoryMock->expects($this->exactly(1))
            ->method('createGlueBackendApiApplication')
            ->willReturn($applicationMock);

        $glueBackendApiApplication = new BackendApiGlueApplicationBootstrapPlugin();
        $glueBackendApiApplication->setFactory($factoryMock);
        $glueBackendApiApplication->getApplication();
    }
}
