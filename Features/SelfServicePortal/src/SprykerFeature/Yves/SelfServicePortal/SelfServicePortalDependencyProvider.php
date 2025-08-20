<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal;

use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SelfServicePortalDependencyProvider extends AbstractBundleDependencyProvider
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
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';

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
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @var string
     */
    public const SERVICE_SELF_SERVICE_PORTAL = 'SERVICE_SELF_SERVICE_PORTAL';

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
        $container = $this->addCompanyUserClient($container);
        $container = $this->addRouterService($container);
        $container = $this->addRequestStackService($container);
        $container = $this->addSelfServicePortalService($container);

        return $container;
    }

    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return $container->getLocator()->customer()->client();
        });

        return $container;
    }

    protected function addCompanyBusinessUnitClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_BUSINESS_UNIT, function (Container $container) {
            return $container->getLocator()->companyBusinessUnit()->client();
        });

        return $container;
    }

    protected function addPermissionClient(Container $container): Container
    {
        $container->set(static::CLIENT_PERMISSION, function (Container $container) {
            return $container->getLocator()->permission()->client();
        });

        return $container;
    }

    protected function addShipmentTypeStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_SHIPMENT_TYPE_STORAGE, function (Container $container) {
            return $container->getLocator()->shipmentTypeStorage()->client();
        });

        return $container;
    }

    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return $container->getLocator()->store()->client();
        });

        return $container;
    }

    protected function addProductOfferStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_STORAGE, function (Container $container) {
            return $container->getLocator()->productOfferStorage()->client();
        });

        return $container;
    }

    protected function addServicePointSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SERVICE_POINT_SEARCH, function (Container $container) {
            return $container->getLocator()->servicePointSearch()->client();
        });

        return $container;
    }

    protected function addTwigService(Container $container): Container
    {
        $container->set(static::TWIG_ENVIRONMENT, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_TWIG);
        });

        return $container;
    }

    protected function addSalesClient(Container $container): Container
    {
        $container->set(static::CLIENT_SALES, function (Container $container) {
            return $container->getLocator()->sales()->client();
        });

        return $container;
    }

    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return $container->getLocator()->glossaryStorage()->client();
        });

        return $container;
    }

    protected function addCompanyUserClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_USER, function (Container $container): CompanyUserClientInterface {
            return $container->getLocator()->companyUser()->client();
        });

        return $container;
    }

    protected function addRouterService(Container $container): Container
    {
        $container->set(static::SERVICE_ROUTER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_ROUTER);
        });

        return $container;
    }

    protected function addRequestStackService(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    protected function addSelfServicePortalService(Container $container): Container
    {
        $container->set(static::SERVICE_SELF_SERVICE_PORTAL, function (Container $container) {
            return $container->getLocator()->selfServicePortal()->service();
        });

        return $container;
    }
}
