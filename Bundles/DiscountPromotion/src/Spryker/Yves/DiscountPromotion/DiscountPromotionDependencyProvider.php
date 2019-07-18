<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DiscountPromotion;

use BadMethodCallException;
use Spryker\Yves\DiscountPromotion\Dependency\Client\DiscountPromotionToProductBridge;
use Spryker\Yves\DiscountPromotion\Dependency\StorageProductMapperPluginInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class DiscountPromotionDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PRODUCT_CLIENT = 'PRODUCT_CLIENT';
    public const AVAILABILITY_CLIENT = 'AVAILABILITY_CLIENT';
    public const PRODUCT_MAPPER_PLUGIN = 'PRODUCT_MAPPER_PLUGIN';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductClient($container);
        $container = $this->addProductMapperPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductClient(Container $container)
    {
        $container[static::PRODUCT_CLIENT] = function (Container $container) {
            return new DiscountPromotionToProductBridge($container->getLocator()->product()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductMapperPlugin(Container $container)
    {
        $container[static::PRODUCT_MAPPER_PLUGIN] = function (Container $container) {
            return $this->getProductMapperPlugin($container);
        };

        return $container;
    }

    /**
     * This plugin is used to map product raw data from yves data store to transfer object
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @throws \BadMethodCallException
     *
     * @return \Spryker\Yves\DiscountPromotion\Dependency\StorageProductMapperPluginInterface
     */
    protected function getProductMapperPlugin(Container $container)
    {
        throw new BadMethodCallException(sprintf(
            'Product mapper plugin is not provided. You must create mapper plugin implementing "%s" interface and provide it with "%s" method.',
            StorageProductMapperPluginInterface::class,
            'DiscountPromotionDependencyProvider::getProductMapperPlugin'
        ));
    }
}
