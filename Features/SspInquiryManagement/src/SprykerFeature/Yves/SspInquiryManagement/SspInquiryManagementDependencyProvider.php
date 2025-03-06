<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement;

use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @method \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 */
class SspInquiryManagementDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SSP_INQUIRY = 'CLIENT_SSP_INQUIRY';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @var string
     */
    public const SERVICE_FILE_MANAGER = 'SERVICE_FILE_MANAGER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addSspInquiryClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addStoreClient($container);
        $container = $this->addRequestStackService($container);
        $container = $this->addRouterService($container);
        $container = $this->addFileManagerService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSspInquiryClient(Container $container): Container
    {
        $container->set(static::CLIENT_SSP_INQUIRY, function (Container $container) {
            return $container->getLocator()->sspInquiryManagement()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addCustomerClient(Container $container): Container
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
    public function addCompanyUserClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_USER, function (Container $container) {
            return $container->getLocator()->companyUser()->client();
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
    protected function addRequestStackService(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRouterService(Container $container): Container
    {
        $container->set(static::SERVICE_ROUTER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_ROUTER);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addFileManagerService(Container $container): Container
    {
        $container->set(static::SERVICE_FILE_MANAGER, function (Container $container): FileManagerServiceInterface {
            return $container->getLocator()->fileManager()->service();
        });

        return $container;
    }
}
