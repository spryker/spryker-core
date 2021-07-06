<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Application\Communication\Entrypoint\Backoffice;

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
 * @group Backoffice
 * @group BackofficeEntrypointTest
 * Add your own group annotations below this line
 */
class BackofficeEntrypointTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Application\ApplicationCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateBackofficeApplication(): void
    {
        $this->assertInstanceOf(ApplicationInterface::class, $this->tester->getFactory()->createBackofficeApplication());
    }
}
