<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Messenger;

use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Messenger\Dependency\Facade\MessengerToGlossaryBridge;

class MessengerDependencyProvider extends AbstractBundleDependencyProvider
{

    const SESSION = 'session';
    const FACADE_GLOSSARY = 'glossary facade';

    /**
     * @param Container $container
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
