<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToLocaleBridge;
use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToMessengerBridge;
use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToTouchBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Glossary\GlossaryConfig getConfig()
 */
class GlossaryDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_TOUCH = 'touch facade';

    public const FACADE_LOCALE = 'locale facade';

    public const PLUGIN_VALIDATOR = 'validator plugin';

    public const FACADE_MESSENGER = 'messages';

    /**
     * @uses \Spryker\Zed\Validator\Communication\Plugin\Application\ValidatorApplicationPlugin::SERVICE_VALIDATOR
     */
    protected const SERVICE_VALIDATOR = 'validator';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new GlossaryToLocaleBridge($container->getLocator()->locale()->facade());
        });

        $container->set(static::PLUGIN_VALIDATOR, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_VALIDATOR);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_TOUCH, function (Container $container) {
            return new GlossaryToTouchBridge($container->getLocator()->touch()->facade());
        });

        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new GlossaryToLocaleBridge($container->getLocator()->locale()->facade());
        });

        $container->set(static::FACADE_MESSENGER, function (Container $container) {
            return new GlossaryToMessengerBridge($container->getLocator()->messenger()->facade());
        });

        return $container;
    }
}
