<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage;

use Exception;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductSetStorage\Dependency\Facade\ProductSetStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductSetStorage\Dependency\Facade\ProductSetStorageToGlossaryFacadeBridge;
use Spryker\Zed\ProductSetStorage\Dependency\Facade\ProductSetStorageToProductImageFacadeBridge;
use Spryker\Zed\ProductSetStorage\Dependency\QueryContainer\ProductSetStorageToProductImageQueryContainerBridge;
use Spryker\Zed\ProductSetStorage\Dependency\QueryContainer\ProductSetStorageToProductSetQueryContainerBridge;

/**
 * @method \Spryker\Zed\ProductSetStorage\ProductSetStorageConfig getConfig()
 */
class ProductSetStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT_SET = 'QUERY_CONTAINER_PRODUCT_SET';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT_IMAGE = 'QUERY_CONTAINER_PRODUCT_IMAGE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';

    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addGlossaryFacade($container);
        $container = $this->addProductImageFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new ProductSetStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT_SET, function (Container $container) {
            return new ProductSetStorageToProductSetQueryContainerBridge($container->getLocator()->productSet()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT_IMAGE, function (Container $container) {
            return new ProductSetStorageToProductImageQueryContainerBridge($container->getLocator()->productImage()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryFacade(Container $container): Container
    {
        $container->set(static::FACADE_GLOSSARY, function (Container $container) {
            $module = 'glossary';
            try {
                return new ProductSetStorageToGlossaryFacadeBridge($container->getLocator()->$module()->facade());
            } catch (FacadeNotFoundException) {
                throw new Exception('Missing "spryker/glossary" module.');
            }
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductImageFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_IMAGE, function (Container $container) {
            return new ProductSetStorageToProductImageFacadeBridge($container->getLocator()->productImage()->facade());
        });

        return $container;
    }
}
