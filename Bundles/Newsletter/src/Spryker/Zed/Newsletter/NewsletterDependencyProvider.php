<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Newsletter\Dependency\Facade\NewsletterToGlossaryBridge;
use Spryker\Zed\Newsletter\Dependency\Facade\NewsletterToMailBridge;

class NewsletterDependencyProvider extends AbstractBundleDependencyProvider
{

    const DOUBLE_OPT_IN_SENDER_PLUGINS = 'double opt in sender plugins';
    const FACADE_MAIL = 'mail facade';
    const FACADE_GLOSSARY = 'glossary facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[self::DOUBLE_OPT_IN_SENDER_PLUGINS] = function (Container $container) {
            return $this->getDoubleOptInSenderPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_MAIL] = function (Container $container) {
            return new NewsletterToMailBridge($container->getLocator()->mail()->facade());
        };
        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new NewsletterToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    protected function getDoubleOptInSenderPlugins(Container $container)
    {
        return [];
    }

}
