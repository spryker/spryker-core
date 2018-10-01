<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary;

use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToLocaleBridge;
use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToMessengerBridge;
use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToTouchBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\Container;

class GlossaryDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_TOUCH = 'touch facade';

    public const FACADE_LOCALE = 'locale facade';

    public const PLUGIN_VALIDATOR = 'validator plugin';

    public const FACADE_MESSENGER = 'messages';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new GlossaryToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::PLUGIN_VALIDATOR] = function () {
            return (new Pimple())->getApplication()['validator'];
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new GlossaryToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new GlossaryToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_MESSENGER] = function (Container $container) {
            return new GlossaryToMessengerBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }
}
