<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
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

class RestRequestValidatorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FILESYSTEM = 'FILESYSTEM';
    public const YAML = 'YAML';
    public const CLIENT_STORE = 'CLIENT_STORE';
    public const VALIDATION = 'VALIDATION';
    public const CONSTRAINT_COLLECTION = 'CONSTRAINT_COLLECTION';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);

        $container = $this->addFilesystem($container);
        $container = $this->addYaml($container);
        $container = $this->addValidation($container);
        $container = $this->addConstraintCollection($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFilesystem(Container $container): Container
    {
        $container[static::FILESYSTEM] = function () {
            return new RestRequestValidatorToFilesystemAdapter();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addYaml(Container $container): Container
    {
        $container[static::YAML] = function () {
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
    protected function addValidation(Container $container): Container
    {
        $container[static::VALIDATION] = function () {
            return new RestRequestValidatorToValidationAdapter();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addConstraintCollection(Container $container): Container
    {
        $container[static::CONSTRAINT_COLLECTION] = function () {
            return new RestRequestValidatorToConstraintCollectionAdapter();
        };

        return $container;
    }
}
