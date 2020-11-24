<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Session\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Spryker\Zed\Session\Communication\Plugin\Application\SessionApplicationPlugin;
use Spryker\Zed\Session\Communication\Plugin\EventDispatcher\SessionEventDispatcherPlugin;
use Spryker\Zed\Session\Communication\SessionCommunicationFactory;
use Spryker\Zed\Session\SessionConfig;
use SprykerTest\Client\Testify\Helper\ClientHelperTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Zed\Application\Helper\ApplicationHelperTrait;
use SprykerTest\Zed\EventDispatcher\Helper\EventDispatcherHelperTrait;
use SprykerTest\Zed\Testify\Helper\Communication\CommunicationHelperTrait;

class SessionHelper extends Module
{
    use ApplicationHelperTrait;
    use EventDispatcherHelperTrait;

    use ConfigHelperTrait;
    use CommunicationHelperTrait;
    use ClientHelperTrait;

    protected const MODULE_NAME = 'Session';

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->ensureCleanSessionState();

        $this->getApplicationHelper()->addApplicationPlugin(
            $this->getSessionApplicationPluginStub()
        );

        $this->getEventDispatcherHelper()->addEventDispatcherPlugin(new SessionEventDispatcherPlugin());
    }

    /**
     * When the SessionApplicationPlugin is booted some cookie params are set.
     * To make sure we have a known session state this method exists.
     *
     * @return void
     */
    protected function ensureCleanSessionState(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        session_set_cookie_params(0, '', '');
    }

    /**
     * @return \Spryker\Zed\Session\Communication\Plugin\Application\SessionApplicationPlugin
     */
    protected function getSessionApplicationPluginStub()
    {
        /** @var \Spryker\Zed\Session\Communication\Plugin\Application\SessionApplicationPlugin $sessionApplicationPlugin */
        $sessionApplicationPlugin = Stub::make(SessionApplicationPlugin::class, [
            'getConfig' => function () {
                return $this->getConfig();
            },
            'getFactory' => function () {
                return $this->getFactory();
            },
            'isSessionTestEnabled' => function () {
                return true;
            },
        ]);

        return $sessionApplicationPlugin;
    }

    /**
     * @return \Spryker\Zed\Session\SessionConfig
     */
    protected function getConfig(): SessionConfig
    {
        /** @var \Spryker\Zed\Session\SessionConfig $sessionConfig */
        $sessionConfig = $this->getConfigHelper()->getModuleConfig(static::MODULE_NAME);

        return $sessionConfig;
    }

    /**
     * @return \Spryker\Zed\Session\Communication\SessionCommunicationFactory
     */
    protected function getFactory(): SessionCommunicationFactory
    {
        /** @var \Spryker\Zed\Session\Communication\SessionCommunicationFactory $sessionFactory */
        $sessionFactory = $this->getCommunicationHelper()->getFactory(static::MODULE_NAME);

        return $sessionFactory;
    }
}
