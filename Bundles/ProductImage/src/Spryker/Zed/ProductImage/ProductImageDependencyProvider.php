<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage;

use Exception;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToGlossaryFacadeBridge;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleBridge;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToStoreFacadeBridge;

/**
 * @method \Spryker\Zed\ProductImage\ProductImageConfig getConfig()
 */
class ProductImageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductImageToLocaleBridge($container->getLocator()->locale()->facade());
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
                return new ProductImageToGlossaryFacadeBridge($container->getLocator()->$module()->facade());
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
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            $module = 'store';
            try {
                return new ProductImageToStoreFacadeBridge($container->getLocator()->$module()->facade());
            } catch (FacadeNotFoundException) {
                throw new Exception('Missing "spryker/store" module.');
            }
        });

        return $container;
    }
}
