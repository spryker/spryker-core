<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\EventDispatcher\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\EventDispatcher\EventDispatcherFactory;
use Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin;
use SprykerTest\Yves\Testify\Helper\ApplicationHelper;
use SprykerTest\Yves\Testify\Helper\FactoryHelper;

class EventDispatcherHelper extends Module
{
    protected const MODULE_NAME = 'EventDispatcher';

    /**
     * @var \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface[]
     */
    protected $eventDispatcherPlugins = [];

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->getApplicationHelper()->addApplicationPlugin(
            $this->getEventDispatcherApplicationPluginStub()
        );
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->eventDispatcherPlugins = [];
    }

    /**
     * @return \Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin
     */
    protected function getEventDispatcherApplicationPluginStub()
    {
        /** @var \Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin $eventDispatcherApplicationPlugin */
        $eventDispatcherApplicationPlugin = Stub::make(EventDispatcherApplicationPlugin::class, [
            'getFactory' => function () {
                return $this->getFactory();
            },
        ]);

        return $eventDispatcherApplicationPlugin;
    }

    /**
     * @param \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface $eventDispatcherPlugin
     *
     * @return $this
     */
    public function addEventDispatcherPlugin(EventDispatcherPluginInterface $eventDispatcherPlugin)
    {
        $this->eventDispatcherPlugins[] = $eventDispatcherPlugin;

        return $this;
    }

    /**
     * @return \Spryker\Yves\EventDispatcher\EventDispatcherFactory
     */
    protected function getFactory(): EventDispatcherFactory
    {
        $factoryHelper = $this->getFactoryHelper();
        $factoryHelper->mockFactoryMethod('getEventDispatcherPlugins', function () {
            return $this->eventDispatcherPlugins;
        }, static::MODULE_NAME);

        /** @var \Spryker\Yves\EventDispatcher\EventDispatcherFactory $eventDispatcherFactory */
        $eventDispatcherFactory = $factoryHelper->getFactory(static::MODULE_NAME);

        return $eventDispatcherFactory;
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
     * @return \SprykerTest\Yves\Testify\Helper\ApplicationHelper
     */
    protected function getApplicationHelper(): ApplicationHelper
    {
        /** @var \SprykerTest\Yves\Testify\Helper\ApplicationHelper $applicationHelper */
        $applicationHelper = $this->getModule('\\' . ApplicationHelper::class);

        return $applicationHelper;
    }
}
