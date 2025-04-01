<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig getConfig()
 */
class SspServiceManagementDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_COMPANY_BUSINESS_UNIT = 'CLIENT_COMPANY_BUSINESS_UNIT';

    /**
     * @var string
     */
    public const CLIENT_PERMISSION = 'CLIENT_PERMISSION';

    /**
     * @var string
     */
    public const CLIENT_SHIPMENT_TYPE_STORAGE = 'CLIENT_SHIPMENT_TYPE_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_OFFER_STORAGE = 'CLIENT_PRODUCT_OFFER_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_SERVICE_POINT_SEARCH = 'CLIENT_SERVICE_POINT_SEARCH';

    /**
     * @var string
     */
    public const CLIENT_SALES = 'CLIENT_SALES';

    /**
     * @var string
     */
    public const TWIG_ENVIRONMENT = 'TWIG_ENVIRONMENT';

    /**
     * @var string
     */
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @uses \Spryker\Yves\Twig\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     *
     * @var string
     */
    protected const SERVICE_TWIG = 'twig';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addCompanyBusinessUnitClient($container);
        $container = $this->addPermissionClient($container);
        $container = $this->addShipmentTypeStorageClient($container);
        $container = $this->addStoreClient($container);
        $container = $this->addProductOfferStorageClient($container);
        $container = $this->addServicePointSearchClient($container);
        $container = $this->addTwigService($container);
        $container = $this->addSalesClient($container);
        $container = $this->addGlossaryStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return $container->getLocator()->customer()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyBusinessUnitClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_BUSINESS_UNIT, function (Container $container) {
            return $container->getLocator()->companyBusinessUnit()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPermissionClient(Container $container): Container
    {
        $container->set(static::CLIENT_PERMISSION, function (Container $container) {
            return $container->getLocator()->permission()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShipmentTypeStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_SHIPMENT_TYPE_STORAGE, function (Container $container) {
            return $container->getLocator()->shipmentTypeStorage()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return $container->getLocator()->store()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductOfferStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_STORAGE, function (Container $container) {
            return $container->getLocator()->productOfferStorage()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addServicePointSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SERVICE_POINT_SEARCH, function (Container $container) {
            return $container->getLocator()->servicePointSearch()->client();
        });

        return $container;
    }

      /**
       * @param \Spryker\Yves\Kernel\Container $container
       *
       * @return \Spryker\Yves\Kernel\Container
       */
    protected function addTwigService(Container $container): Container
    {
        $container->set(static::TWIG_ENVIRONMENT, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_TWIG);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSalesClient(Container $container): Container
    {
        $container->set(static::CLIENT_SALES, function (Container $container) {
            return $container->getLocator()->sales()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return $container->getLocator()->glossaryStorage()->client();
        });

        return $container;
    }
}
