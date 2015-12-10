<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary;

use SprykerFeature\Zed\Application\Communication\Plugin\Pimple;
use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class GlossaryDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_TOUCH = 'touch facade';

    const FACADE_LOCALE = 'locale facade';

    const PLUGIN_VALIDATOR = 'validator plugin';

    const MESSAGES = 'messages';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        $container[self::PLUGIN_VALIDATOR] = function () {
            return (new Pimple())->getApplication()['validator'];
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->facade();
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        $container[self::MESSAGES] = function (Container $container) {
            return $container->getLocator()->messenger()->facade();
        };

        return $container;
    }

}
