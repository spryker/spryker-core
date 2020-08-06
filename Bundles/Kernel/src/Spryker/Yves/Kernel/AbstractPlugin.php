<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Kernel\Container\GlobalContainer;

abstract class AbstractPlugin
{
    use FactoryResolverAwareTrait;
    use ClientResolverAwareTrait;
    use BundleConfigResolverAwareTrait;

    protected const SERVICE_LOCALE = 'locale';

    /**
     * @var \Spryker\Service\Container\ContainerInterface|null
     */
    protected static $container;

    /**
     * @var string|null
     */
    protected static $locale;

    /**
     * @deprecated Use {@link \Spryker\Yves\Kernel\AbstractPlugin::getContainer()} instead.
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function getApplication()
    {
        return $this->getContainer();
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        if (static::$container === null) {
            static::$container = (new GlobalContainer())->getContainer();
        }

        return static::$container;
    }

    /**
     * @return string
     */
    protected function getLocale()
    {
        if (static::$locale === null) {
            static::$locale = $this->getContainer()->get(static::SERVICE_LOCALE);
        }

        return static::$locale;
    }
}
