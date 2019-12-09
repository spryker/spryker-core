<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\EventDispatcher\Helper;

use Codeception\Stub;
use Spryker\Yves\EventDispatcher\EventDispatcherFactory;
use Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin;
use SprykerTest\Shared\EventDispatcher\Helper\AbstractEventDispatcherHelper;
use SprykerTest\Yves\Testify\Helper\ApplicationHelperTrait;
use SprykerTest\Yves\Testify\Helper\FactoryHelperTrait;

class EventDispatcherHelper extends AbstractEventDispatcherHelper
{
    use ApplicationHelperTrait;
    use FactoryHelperTrait;

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
}
