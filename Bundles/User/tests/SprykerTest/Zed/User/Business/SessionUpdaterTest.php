<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Business;

use Codeception\Test\Unit;
use Spryker\Zed\User\Business\Model\UserSessionUpdater;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group User
 * @group Business
 * @group SessionUpdaterTest
 * Add your own group annotations below this line
 */
class SessionUpdaterTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\User\UserBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $sessionClient;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->sessionClient = $this->tester->getLocator()->session()->client();
    }

    /**
     * @return void
     */
    public function testSessionSuccessfullyMigrated(): void
    {
        $this->sessionClient->start();
        $oldSessionCreatedTime = $this->sessionClient
            ->getMetadataBag()
            ->getCreated();
        sleep(1);
        $sessionUpdater = new UserSessionUpdater($this->sessionClient);
        $result = $sessionUpdater->updateTtl();
        $newSessionCreatedTime = $this->sessionClient
            ->getMetadataBag()
            ->getCreated();

        $this->assertTrue($result);
        $this->assertGreaterThan($oldSessionCreatedTime, $newSessionCreatedTime);
    }
}
