<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilValidate;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Service\UtilValidate\Dependency\External\EguliasRfcEmailValidatorAdapter;

class UtilValidateDependencyProvider extends AbstractBundleDependencyProvider
{
    const ADAPTER_EMAIL_VALIDATOR = 'ADAPTER_EMAIL_VALIDATOR';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = parent::provideServiceDependencies($container);

        $container = $this->addEmailValidatorAdapter($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addEmailValidatorAdapter(Container $container)
    {
        $container[static::ADAPTER_EMAIL_VALIDATOR] = function (Container $container) {
            return $this->createEmailValidatorAdapter($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\UtilValidate\Dependency\External\EmailValidatorAdapterInterface
     */
    protected function createEmailValidatorAdapter(Container $container)
    {
        return new EguliasRfcEmailValidatorAdapter();
    }
}
