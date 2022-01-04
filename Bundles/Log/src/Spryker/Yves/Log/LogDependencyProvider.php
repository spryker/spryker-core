<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Log;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Log\Dependency\Client\LogToLocaleClientBridge;

/**
 * @method \Spryker\Yves\Log\LogConfig getConfig()
 */
class LogDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_QUEUE = 'queue client';

    /**
     * @var string
     */
    public const LOG_PROCESSORS = 'LOG_PROCESSORS';

    /**
     * @var string
     */
    public const LOG_HANDLERS = 'LOG_HANDLERS';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addQueueClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addLogHandlers($container);
        $container = $this->addProcessors($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQueueClient(Container $container)
    {
        $container->set(static::CLIENT_QUEUE, function () use ($container) {
            return $container->getLocator()->queue()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLogHandlers(Container $container)
    {
        $container->set(static::LOG_HANDLERS, function () {
            return [];
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProcessors(Container $container)
    {
        $container->set(static::LOG_PROCESSORS, function () {
            return [];
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new LogToLocaleClientBridge($container->getLocator()->locale()->client());
        });

        return $container;
    }
}
