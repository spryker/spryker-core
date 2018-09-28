<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilValidate;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Service\UtilValidate\Dependency\External\UtilValidateToEguliasRfcEmailValidatorAdapter;

class UtilValidateDependencyProvider extends AbstractBundleDependencyProvider
{
    public const EMAIL_VALIDATOR = 'EMAIL_VALIDATOR';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = parent::provideServiceDependencies($container);

        $container = $this->addEmailValidator($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addEmailValidator(Container $container)
    {
        $container[static::EMAIL_VALIDATOR] = function (Container $container) {
            return $this->createEmailValidator($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\UtilValidate\Dependency\External\UtilValidateToEmailValidatorInterface
     */
    protected function createEmailValidator(Container $container)
    {
        return new UtilValidateToEguliasRfcEmailValidatorAdapter();
    }
}
