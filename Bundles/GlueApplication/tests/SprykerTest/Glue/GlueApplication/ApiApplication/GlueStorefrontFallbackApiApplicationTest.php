<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\ApiApplication;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\ApiApplication\GlueStorefrontFallbackApiApplication;
use Spryker\Shared\Application\ApplicationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group ApiApplication
 * @group GlueStorefrontFallbackApiApplicationTest
 * Add your own group annotations below this line
 */
class GlueStorefrontFallbackApiApplicationTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueApplication\GlueApplicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testApplicationIsBooted(): void
    {
        $glueBackendApiApplication = new GlueStorefrontFallbackApiApplication($this->tester->getContainer(), []);
        $bootedApplication = $glueBackendApiApplication->boot();

        $this->assertInstanceOf(ApplicationInterface::class, $bootedApplication);
    }
}
