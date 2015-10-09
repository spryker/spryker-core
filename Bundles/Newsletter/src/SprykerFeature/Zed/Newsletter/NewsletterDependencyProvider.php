<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class NewsletterDependencyProvider extends AbstractBundleDependencyProvider
{
    const OPT_IN_SENDER_PLUGINS = 'opt in sender plugins';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[self::OPT_IN_SENDER_PLUGINS] = function (Container $container) {
            return $this->getOptInSenderPlugins($container);
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return array
     */
    protected function getOptInSenderPlugins(Container $container)
    {
        return [];
    }
}
