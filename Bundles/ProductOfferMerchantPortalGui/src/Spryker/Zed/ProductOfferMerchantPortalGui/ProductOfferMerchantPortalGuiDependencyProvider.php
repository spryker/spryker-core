<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceBridge;

class ProductOfferMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_USER = 'FACADE_USER';
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    public const PROPEL_QUERY_PRODUCT_CONCRETE = 'PROPEL_QUERY_PRODUCT_CONCRETE';
    public const PROPEL_QUERY_PRODUCT_IMAGE = 'PROPEL_QUERY_PRODUCT_IMAGE';
    public const PROPEL_QUERY_PRODUCT_OFFER = 'PROPEL_QUERY_PRODUCT_OFFER';
    public const PROPEL_QUERY_STORE = 'PROPEL_QUERY_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addLocaleFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addUtilDateTimeService($container);
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addTranslatorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addProductConcretePropelQuery($container);
        $container = $this->addProductImagePropelQuery($container);
        $container = $this->addProductOfferPropelQuery($container);
        $container = $this->addStorePropelQuery($container);
        $container = $this->addUtilEncodingService($container);

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
            return new ProductOfferMerchantPortalGuiToLocaleFacadeBridge(
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
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDateTimeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATE_TIME, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToUtilDateTimeServiceBridge(
                $container->getLocator()->utilDateTime()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToMerchantUserFacadeBridge(
                $container->getLocator()->merchantUser()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslatorFacade(Container $container): Container
    {
        $container->set(static::FACADE_TRANSLATOR, function (Container $container) {
            return new ProductOfferMerchantPortalGuiToTranslatorFacadeBridge(
                $container->getLocator()->translator()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcretePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_CONCRETE, $container->factory(function () {
            return SpyProductQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductImagePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_IMAGE, $container->factory(function () {
            return SpyProductImageQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOfferPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_OFFER, $container->factory(function () {
            return SpyProductOfferQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STORE, $container->factory(function () {
            return SpyStoreQuery::create();
        }));

        return $container;
    }
}
