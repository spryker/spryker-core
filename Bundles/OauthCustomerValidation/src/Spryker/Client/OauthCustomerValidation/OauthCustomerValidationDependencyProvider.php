<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCustomerValidation;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\OauthCustomerValidation\Dependency\Client\OauthCustomerValidationToCustomerStorageClientBridge;
use Spryker\Client\OauthCustomerValidation\Dependency\Client\OauthCustomerValidationToCustomerStorageClientInterface;
use Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToOauthServiceBridge;
use Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToOauthServiceInterface;
use Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToUtilEncodingServiceBridge;
use Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToUtilEncodingServiceInterface;

class OauthCustomerValidationDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CUSTOMER_STORAGE = 'CLIENT_CUSTOMER_STORAGE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const SERVICE_OAUTH = 'SERVICE_OAUTH';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addCustomerStorageClient($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addOauthService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER_STORAGE, function (Container $container): OauthCustomerValidationToCustomerStorageClientInterface {
            return new OauthCustomerValidationToCustomerStorageClientBridge(
                $container->getLocator()->customerStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container): OauthCustomerValidationToUtilEncodingServiceInterface {
            return new OauthCustomerValidationToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addOauthService(Container $container): Container
    {
        $container->set(static::SERVICE_OAUTH, function (Container $container): OauthCustomerValidationToOauthServiceInterface {
            return new OauthCustomerValidationToOauthServiceBridge(
                $container->getLocator()->oauth()->service(),
            );
        });

        return $container;
    }
}
