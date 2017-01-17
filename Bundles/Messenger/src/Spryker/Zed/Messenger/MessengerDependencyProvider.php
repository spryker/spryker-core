<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger;

use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Messenger\Dependency\Facade\MessengerToGlossaryBridge;

class MessengerDependencyProvider extends AbstractBundleDependencyProvider
{

    const SESSION = 'session';
    const FACADE_GLOSSARY = 'glossary facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::SESSION] = function (Container $container) {
            return (new Pimple())->getApplication()['request']->getSession();
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new MessengerToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        return $container;
    }

}
