<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeBridge;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToMoneyFacadeBridge;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToPriceProductFacadeBridge;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductAttributeFacadeBridge;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductFacadeBridge;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductRelationFacadeBridge;
use Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToPropelQueryBuilderQueryContainerBridge;
use Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\ProductRelationGui\ProductRelationGuiConfig getConfig()
 */
class ProductRelationGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_RELATION = 'FACADE_PRODUCT_RELATION';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';
    public const FACADE_PRODUCT_ATTRIBUTE = 'FACADE_PRODUCT_ATTRIBUTE';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    public const PROPEL_QUERY_PRODUCT_RELATION = 'PROPEL_QUERY_PRODUCT_RELATION';
    public const PROPEL_QUERY_PRODUCT_ABSTRACT = 'PROPEL_QUERY_PRODUCT_ABSTRACT';
    public const PROPEL_QUERY_PRODUCT_ATTRIBUTE_KEY = 'PROPEL_QUERY_PRODUCT_ATTRIBUTE_KEY';

    public const QUERY_CONTAINER_PROPEL_QUERY_BUILDER = 'QUERY_CONTAINER_PROPEL_QUERY_BUILDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addProductRelationFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addPriceProductFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addProductAttributeFacade($container);
        $container = $this->addProductRelationPropelQuery($container);
        $container = $this->addProductAbstractPropelQuery($container);
        $container = $this->addProductAttributeKeyPropelQuery($container);
        $container = $this->addPropelQueryBuilderQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelQueryBuilderQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_PROPEL_QUERY_BUILDER, function (Container $container) {
            return new ProductRelationGuiToPropelQueryBuilderQueryContainerBridge(
                $container->getLocator()->propelQueryBuilder()->queryContainer()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAttributeKeyPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_ATTRIBUTE_KEY, $container->factory(function () {
            return SpyProductAttributeKeyQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_ABSTRACT, $container->factory(function () {
            return SpyProductAbstractQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductRelationPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_RELATION, $container->factory(function () {
            return SpyProductRelationQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAttributeFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_ATTRIBUTE, function (Container $container) {
            return new ProductRelationGuiToProductAttributeFacadeBridge(
                $container->getLocator()->productAttribute()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE_PRODUCT, function (Container $container) {
            return new ProductRelationGuiToPriceProductFacadeBridge(
                $container->getLocator()->priceProduct()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container): Container
    {
        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new ProductRelationGuiToMoneyFacadeBridge(
                $container->getLocator()->money()->facade()
            );
        });

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
            return new ProductRelationGuiToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductRelationFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_RELATION, function (Container $container) {
            return new ProductRelationGuiToProductRelationFacadeBridge(
                $container->getLocator()->productRelation()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductRelationGuiToProductFacadeBridge(
                $container->getLocator()->product()->facade()
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
            return new ProductRelationGuiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }
}
