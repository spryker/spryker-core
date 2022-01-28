<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToApiFacadeBridge;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToMerchantRelationshipFacadeBridge;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Service\MerchantRelationshipApiToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\MerchantRelationshipApi\MerchantRelationshipApiConfig getConfig()
 */
class MerchantRelationshipApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MERCHANT_RELATIONSHIP = 'FACADE_MERCHANT_RELATIONSHIP';

    /**
     * @var string
     */
    public const FACADE_API = 'FACADE_API';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addMerchantRelationshipFacade($container);
        $container = $this->addApiFacade($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_RELATIONSHIP, function (Container $container) {
            return new MerchantRelationshipApiToMerchantRelationshipFacadeBridge(
                $container->getLocator()->merchantRelationship()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApiFacade(Container $container): Container
    {
        $container->set(static::FACADE_API, function (Container $container) {
            return new MerchantRelationshipApiToApiFacadeBridge(
                $container->getLocator()->api()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new MerchantRelationshipApiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }
}
