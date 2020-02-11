<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SessionRedis\Communication\Plugin\Session;

use Codeception\Test\Unit;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface;
use Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReaderInterface;
use Spryker\Zed\SessionRedis\Communication\Plugin\Session\ZedSessionRedisLockReleaserPlugin;
use Spryker\Zed\SessionRedis\Communication\SessionRedisCommunicationFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SessionRedis
 * @group Communication
 * @group Plugin
 * @group Session
 * @group SessionLockReleaserPluginTest
 * Add your own group annotations below this line
 */
class SessionLockReleaserPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testCanReleaseLock(): void
    {
        $sessionId = 'session_id';
        $locker = $this->getSpinLockLocker();
        $lockReader = $this->getSessionLockReader();
        $lockReleaserPlugin = new ZedSessionRedisLockReleaserPlugin();

        $locker->lock($sessionId);
        $this->assertNotEmpty($lockReader->getTokenForSession($sessionId));

        $lockReleaserPlugin->release($sessionId);
        $this->assertEmpty($lockReader->getTokenForSession($sessionId));
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface
     */
    protected function getSpinLockLocker(): SessionLockerInterface
    {
        return $this->getFactory()
            ->createSessionHandlerFactory()
            ->createSessionSpinLockLocker(
                $this->getFactory()->createZedSessionRedisWrapper()
            );
    }

    /**
     * @return \Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReaderInterface
     */
    protected function getSessionLockReader(): SessionLockReaderInterface
    {
        return $this->getFactory()->createRedisSessionLockReader(
            $this->getFactory()->createZedSessionRedisWrapper()
        );
    }

    /**
     * @return \Spryker\Zed\SessionRedis\Communication\SessionRedisCommunicationFactory
     */
    protected function getFactory(): SessionRedisCommunicationFactory
    {
        return new SessionRedisCommunicationFactory();
    }
}
