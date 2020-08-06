<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Session\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Spryker\Client\Session\SessionClient;
use Spryker\Yves\Session\Plugin\Application\SessionApplicationPlugin;
use Spryker\Yves\Session\Plugin\EventDispatcher\SessionEventDispatcherPlugin;
use Spryker\Yves\Session\SessionConfig;
use Spryker\Yves\Session\SessionFactory;
use SprykerTest\Client\Testify\Helper\ClientHelperTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Yves\Application\Helper\ApplicationHelperTrait;
use SprykerTest\Yves\EventDispatcher\Helper\EventDispatcherHelperTrait;
use SprykerTest\Yves\Testify\Helper\FactoryHelperTrait;

class SessionHelper extends Module
{
    use ApplicationHelperTrait;
    use EventDispatcherHelperTrait;
    use ConfigHelperTrait;
    use FactoryHelperTrait;
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
     * @return \Spryker\Yves\Session\Plugin\Application\SessionApplicationPlugin
     */
    protected function getSessionApplicationPluginStub()
    {
        /** @var \Spryker\Yves\Session\Plugin\Application\SessionApplicationPlugin $sessionApplicationPlugin */
        $sessionApplicationPlugin = Stub::make(SessionApplicationPlugin::class, [
            'getConfig' => function () {
                return $this->getConfig();
            },
            'getFactory' => function () {
                return $this->getFactory();
            },
            'getClient' => function () {
                return $this->getClient();
            },
            'isSessionTestEnabled' => function () {
                return true;
            },
        ]);

        return $sessionApplicationPlugin;
    }

    /**
     * @return \Spryker\Yves\Session\SessionConfig
     */
    protected function getConfig(): SessionConfig
    {
        /** @var \Spryker\Yves\Session\SessionConfig $sessionConfig */
        $sessionConfig = $this->getConfigHelper()->getModuleConfig(static::MODULE_NAME);

        return $sessionConfig;
    }

    /**
     * @return \Spryker\Yves\Session\SessionFactory
     */
    protected function getFactory(): SessionFactory
    {
        /** @var \Spryker\Yves\Session\SessionFactory $sessionFactory */
        $sessionFactory = $this->getFactoryHelper()->getFactory(static::MODULE_NAME);

        return $sessionFactory;
    }

    /**
     * @return \Spryker\Client\Session\SessionClient
     */
    protected function getClient(): SessionClient
    {
        /** @var \Spryker\Client\Session\SessionClient $sessionClient */
        $sessionClient = $this->getClientHelper()->getClient(static::MODULE_NAME);

        return $sessionClient;
    }
}
