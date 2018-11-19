<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\RestRequestValidator\Dependency\Client\RestRequestValidatorToStoreClientBridge;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToConstraintCollectionAdapter;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapter;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToValidationAdapter;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapter;

/**
 * @method \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig getConfig()
 */
class RestRequestValidatorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const ADAPTER_FILESYSTEM = 'ADAPTER_FILESYSTEM';
    public const ADAPTER_YAML = 'ADAPTER_YAML';
    public const ADAPTER_VALIDATION = 'ADAPTER_VALIDATION';
    public const ADAPTER_CONSTRAINT_COLLECTION = 'ADAPTER_CONSTRAINT_COLLECTION';
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addFilesystemAdapter($container);
        $container = $this->addYamlAdapter($container);
        $container = $this->addValidationAdapter($container);
        $container = $this->addConstraintCollectionAdapter($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFilesystemAdapter(Container $container): Container
    {
        $container[static::ADAPTER_FILESYSTEM] = function () {
            return new RestRequestValidatorToFilesystemAdapter();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addYamlAdapter(Container $container): Container
    {
        $container[static::ADAPTER_YAML] = function () {
            return new RestRequestValidatorToYamlAdapter();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container[static::CLIENT_STORE] = function (Container $container) {
            return new RestRequestValidatorToStoreClientBridge($container->getLocator()->store()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addValidationAdapter(Container $container): Container
    {
        $container[static::ADAPTER_VALIDATION] = function () {
            return new RestRequestValidatorToValidationAdapter();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addConstraintCollectionAdapter(Container $container): Container
    {
        $container[static::ADAPTER_CONSTRAINT_COLLECTION] = function () {
            return new RestRequestValidatorToConstraintCollectionAdapter();
        };

        return $container;
    }
}
