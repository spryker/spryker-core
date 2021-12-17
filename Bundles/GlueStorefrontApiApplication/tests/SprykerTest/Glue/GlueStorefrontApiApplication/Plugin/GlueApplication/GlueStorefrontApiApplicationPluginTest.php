<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\StorefrontApiGlueApplicationBootstrapPlugin;
use Spryker\Shared\Application\ApplicationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group Plugin
 * @group GlueApplication
 * @group GlueStorefrontApiApplicationPluginTest
 * Add your own group annotations below this line
 */
class GlueStorefrontApiApplicationPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testIfApplicationIsServing(): void
    {
        $glueStorefrontApiApplication = new StorefrontApiGlueApplicationBootstrapPlugin();

        $this->assertFalse($glueStorefrontApiApplication->isServing(new GlueApiContextTransfer()));
    }

    /**
     * @return void
     */
    public function testRunWillCreateApplication(): void
    {
        $applicationMock = $this->createMock(ApplicationInterface::class);

        $factoryMock = $this->createMock(GlueStorefrontApiApplicationFactory::class);
        $factoryMock->expects($this->exactly(1))
            ->method('createGlueStorefrontApiApplication')
            ->willReturn($applicationMock);

        $glueStorefrontApiApplication = new StorefrontApiGlueApplicationBootstrapPlugin();
        $glueStorefrontApiApplication->setFactory($factoryMock);
        $glueStorefrontApiApplication->getApplication();
    }
}
