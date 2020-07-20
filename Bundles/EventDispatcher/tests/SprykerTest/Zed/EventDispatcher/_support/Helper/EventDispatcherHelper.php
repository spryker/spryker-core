<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\EventDispatcher\Helper;

use Codeception\Stub;
use Spryker\Zed\EventDispatcher\Communication\EventDispatcherCommunicationFactory;
use Spryker\Zed\EventDispatcher\Communication\Plugin\Application\EventDispatcherApplicationPlugin;
use SprykerTest\Shared\EventDispatcher\Helper\AbstractEventDispatcherHelper;
use SprykerTest\Zed\Application\Helper\ApplicationHelperTrait;
use SprykerTest\Zed\Testify\Helper\Communication\CommunicationHelperTrait;

class EventDispatcherHelper extends AbstractEventDispatcherHelper
{
    use ApplicationHelperTrait;
    use CommunicationHelperTrait;

    /**
     * @return \Spryker\Zed\EventDispatcher\Communication\Plugin\Application\EventDispatcherApplicationPlugin
     */
    protected function getEventDispatcherApplicationPluginStub()
    {
        /** @var \Spryker\Zed\EventDispatcher\Communication\Plugin\Application\EventDispatcherApplicationPlugin $eventDispatcherApplicationPlugin */
        $eventDispatcherApplicationPlugin = Stub::make(EventDispatcherApplicationPlugin::class, [
            'getFactory' => function () {
                return $this->getFactory();
            },
        ]);

        return $eventDispatcherApplicationPlugin;
    }

    /**
     * @return \Spryker\Zed\EventDispatcher\Communication\EventDispatcherCommunicationFactory
     */
    protected function getFactory(): EventDispatcherCommunicationFactory
    {
        $communicationHelper = $this->getCommunicationHelper();
        $communicationHelper->mockFactoryMethod('getEventDispatcherPlugins', function () {
            return $this->eventDispatcherPlugins;
        }, static::MODULE_NAME);

        /** @var \Spryker\Zed\EventDispatcher\Communication\EventDispatcherCommunicationFactory $eventDispatcherFactory */
        $eventDispatcherFactory = $communicationHelper->getFactory(static::MODULE_NAME);

        return $eventDispatcherFactory;
    }
}
