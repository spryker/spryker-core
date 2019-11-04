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
use SprykerTest\Client\Testify\Helper\ClientHelper;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;
use SprykerTest\Yves\EventDispatcher\Helper\EventDispatcherHelper;
use SprykerTest\Yves\Testify\Helper\ApplicationHelper;
use SprykerTest\Yves\Testify\Helper\FactoryHelper;

class SessionHelper extends Module
{
    protected const MODULE_NAME = 'Session';

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->getApplicationHelper()->addApplicationPlugin(
            $this->getSessionApplicationPluginStub()
        );

        $this->getEventDispatcherHelper()->addEventDispatcherPlugin(new SessionEventDispatcherPlugin());
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
     * @return \SprykerTest\Shared\Testify\Helper\ConfigHelper
     */
    protected function getConfigHelper(): ConfigHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\ConfigHelper $configHelper */
        $configHelper = $this->getModule('\\' . ConfigHelper::class);

        return $configHelper;
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
     * @return \SprykerTest\Yves\Testify\Helper\FactoryHelper
     */
    protected function getFactoryHelper(): FactoryHelper
    {
        /** @var \SprykerTest\Yves\Testify\Helper\FactoryHelper $factoryHelper */
        $factoryHelper = $this->getModule('\\' . FactoryHelper::class);

        return $factoryHelper;
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

    /**
     * @return \SprykerTest\Client\Testify\Helper\ClientHelper
     */
    protected function getClientHelper(): ClientHelper
    {
        /** @var \SprykerTest\Client\Testify\Helper\ClientHelper $clientHelper */
        $clientHelper = $this->getModule('\\' . ClientHelper::class);

        return $clientHelper;
    }

    /**
     * @return \SprykerTest\Yves\Testify\Helper\ApplicationHelper
     */
    protected function getApplicationHelper(): ApplicationHelper
    {
        /** @var \SprykerTest\Yves\Testify\Helper\ApplicationHelper $applicationHelper */
        $applicationHelper = $this->getModule('\\' . ApplicationHelper::class);

        return $applicationHelper;
    }

    /**
     * @return \SprykerTest\Yves\EventDispatcher\Helper\EventDispatcherHelper
     */
    protected function getEventDispatcherHelper(): EventDispatcherHelper
    {
        /** @var \SprykerTest\Yves\EventDispatcher\Helper\EventDispatcherHelper $eventDispatcherHelper */
        $eventDispatcherHelper = $this->getModule('\\' . EventDispatcherHelper::class);

        return $eventDispatcherHelper;
    }
}
