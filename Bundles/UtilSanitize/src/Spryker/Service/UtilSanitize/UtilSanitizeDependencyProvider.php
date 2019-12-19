<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitize;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Service\UtilSanitize\Dependency\External\UtilSanitizeToVokuAntiXssAdapter;

class UtilSanitizeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const XSS_SANITIZER = 'XSS_SANITIZER';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container): Container
    {
        $container = parent::provideServiceDependencies($container);
        $container = $this->addXssSanitizer($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function addXssSanitizer(Container $container): Container
    {
        $container->set(static::XSS_SANITIZER, function () {
            return new UtilSanitizeToVokuAntiXssAdapter();
        });

        return $container;
    }
}
