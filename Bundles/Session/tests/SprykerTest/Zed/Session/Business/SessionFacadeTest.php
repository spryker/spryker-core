<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Session\Business;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Zed\Session\Business\Exception\NotALockingSessionHandlerException;
use Spryker\Zed\Session\Communication\Plugin\ServiceProvider\SessionServiceProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Session
 * @group Business
 * @group Facade
 * @group SessionFacadeTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\Session\SessionBusinessTester $tester
 */
class SessionFacadeTest extends Unit
{
    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $application = new Application();
        $application['session.test'] = false;
        $sessionServiceProvider = new SessionServiceProvider();
        $sessionServiceProvider->register($application);
    }

    /**
     * @dataProvider supportingLockSessionHandler
     *
     * @param string $sessionHandler
     *
     * @return void
     */
    public function testRemoveYvesSessionLockForReleasesLockWhenHandlerSupportsLocking($sessionHandler)
    {
        $this->tester->setConfig(SessionConstants::YVES_SESSION_SAVE_HANDLER, $sessionHandler);

        $sessionFacade = $this->tester->getLocator()->session()->facade();
        $sessionFacade->removeYvesSessionLockFor(session_id());
    }

    /**
     * @dataProvider notSupportingLockSessionHandler
     *
     * @param string $sessionHandler
     *
     * @return void
     */
    public function testRemoveYvesSessionLockForThrowsExceptionWhenSessionHandlerDoesNotSupportLocking($sessionHandler)
    {
        $this->tester->setConfig(SessionConstants::YVES_SESSION_SAVE_HANDLER, $sessionHandler);

        $this->expectException(NotALockingSessionHandlerException::class);

        $sessionFacade = $this->tester->getLocator()->session()->facade();
        $sessionFacade->removeYvesSessionLockFor(session_id());
    }

    /**
     * @dataProvider supportingLockSessionHandler
     *
     * @param string $sessionHandler
     *
     * @return void
     */
    public function testRemoveZedSessionLockForReleasesLockWhenHandlerSupportsLocking($sessionHandler)
    {
        $this->tester->setConfig(SessionConstants::ZED_SESSION_SAVE_HANDLER, $sessionHandler);

        $sessionFacade = $this->tester->getLocator()->session()->facade();
        $sessionFacade->removeZedSessionLockFor(session_id());
    }

    /**
     * @dataProvider notSupportingLockSessionHandler
     *
     * @param string $sessionHandler
     *
     * @return void
     */
    public function testRemoveZedSessionLockForThrowsExceptionWhenSessionHandlerDoesNotSupportLocking($sessionHandler)
    {
        $this->tester->setConfig(SessionConstants::ZED_SESSION_SAVE_HANDLER, $sessionHandler);

        $this->expectException(NotALockingSessionHandlerException::class);

        $sessionFacade = $this->tester->getLocator()->session()->facade();
        $sessionFacade->removeZedSessionLockFor(session_id());
    }

    /**
     * @return array
     */
    public function supportingLockSessionHandler()
    {
        return [
            [SessionConstants::SESSION_HANDLER_REDIS_LOCKING],
        ];
    }

    /**
     * @return array
     */
    public function notSupportingLockSessionHandler()
    {
        return [
            [SessionConstants::SESSION_HANDLER_REDIS],
            [SessionConstants::SESSION_HANDLER_FILE],
        ];
    }
}
