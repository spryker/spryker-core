<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToCompanyUserStorageClientBridge;

/**
 * @method \Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig getConfig()
 */
class SharedCartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_COMPANY_USER_STORAGE = 'CLIENT_COMPANY_USER_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCompanyUserStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCompanyUserStorageClient(Container $container): Container
    {
        $container[static::CLIENT_COMPANY_USER_STORAGE] = function (Container $container) {
            return new SharedCartsRestApiToCompanyUserStorageClientBridge(
                $container->getLocator()->companyUserStorage()->client()
            );
        };

        return $container;
    }
}
