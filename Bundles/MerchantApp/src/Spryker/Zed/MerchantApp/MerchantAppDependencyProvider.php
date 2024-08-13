<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToKernelAppFacadeBridge;
use Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToKernelAppFacadeInterface;
use Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToMerchantUserFacadeBridge;
use Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantApp\Dependency\Service\MerchantAppToUtilEncodingServiceBridge;
use Spryker\Zed\MerchantApp\Dependency\Service\MerchantAppToUtilEncodingServiceInterface;

/**
 * @method \Spryker\Zed\MerchantApp\MerchantAppConfig getConfig()
 */
class MerchantAppDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_KERNEL_APP = 'MERCHANT_APP:FACADE_KERNEL_APP';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_USER = 'MERCHANT_APP:FACADE_MERCHANT_USER';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'MERCHANT_APP:SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->provideKernelAppFacade($container);
        $container = $this->provideMerchantUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->provideUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideKernelAppFacade(Container $container): Container
    {
        $container->set(static::FACADE_KERNEL_APP, function (Container $container): MerchantAppToKernelAppFacadeInterface {
            return new MerchantAppToKernelAppFacadeBridge($container->getLocator()->kernelApp()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container): MerchantAppToMerchantUserFacadeInterface {
            // @codeCoverageIgnoreStart
            // This facade is never used when testing the MerchantApp module.
            return new MerchantAppToMerchantUserFacadeBridge($container->getLocator()->merchantUser()->facade());
            // @codeCoverageIgnoreEnd
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container): MerchantAppToUtilEncodingServiceInterface {
            // @codeCoverageIgnoreStart
            // This facade is never used when testing the MerchantApp module.
            return new MerchantAppToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
            // @codeCoverageIgnoreEnd
        });

        return $container;
    }
}
