<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspAssetManagement;

use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @method \SprykerFeature\Yves\SspAssetManagement\SspAssetManagementConfig getConfig()
 */
class SspAssetManagementDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FORM_FACTORY = 'FORM_FACTORY';

    /**
     * @var string
     */
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';

    /**
     * @var string
     */
    public const SERVICE_FILE_MANAGER = 'SERVICE_FILE_MANAGER';

    /**
     * @var string
     */
    public const CLIENT_SSP_ASSET_MANAGEMENT = 'CLIENT_SSP_ASSET_MANAGEMENT';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCompanyUserClient($container);
        $container = $this->addSspAssetManagementClient($container);
        $container = $this->addFileManagerService($container);
        $container = $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyUserClient(Container $container): Container
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
    protected function addSspAssetManagementClient(Container $container): Container
    {
        $container->set(static::CLIENT_SSP_ASSET_MANAGEMENT, function (Container $container) {
            return $container->getLocator()->sspAssetManagement()->client();
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
}
