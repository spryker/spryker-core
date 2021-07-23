<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Application\Communication\Entrypoint\BackendGateway;

use Codeception\Test\Unit;
use Spryker\Shared\Application\ApplicationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Application
 * @group Communication
 * @group Entrypoint
 * @group BackendGateway
 * @group BackendGatewayEntrypointTest
 * Add your own group annotations below this line
 */
class BackendGatewayEntrypointTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Application\ApplicationCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateGatewayApplication(): void
    {
        $this->assertInstanceOf(ApplicationInterface::class, $this->tester->getFactory()->createBackendGatewayApplication());
    }
}
