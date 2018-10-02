<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Log;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class LogDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_QUEUE = 'queue client';

    public const LOG_PROCESSORS = 'LOG_PROCESSORS';
    public const LOG_HANDLERS = 'LOG_HANDLERS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addQueueClient($container);
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
        $container[static::CLIENT_QUEUE] = function () use ($container) {
            return $container->getLocator()->queue()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLogHandlers(Container $container)
    {
        $container[static::LOG_HANDLERS] = function () {
            return [];
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProcessors(Container $container)
    {
        $container[static::LOG_PROCESSORS] = function () {
            return [];
        };

        return $container;
    }
}
