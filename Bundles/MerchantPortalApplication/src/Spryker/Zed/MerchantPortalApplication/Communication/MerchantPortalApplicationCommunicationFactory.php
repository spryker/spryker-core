<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantPortalApplication\Communication;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Kernel\Container\ContainerProxy;
use Spryker\Shared\MerchantPortalApplication\MerchantPortalApplication;
use Spryker\Shared\MerchantPortalApplication\MerchantPortalApplicationInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantPortalApplication\MerchantPortalApplicationDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantPortalApplication\MerchantPortalApplicationConfig getConfig()
 */
class MerchantPortalApplicationCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\MerchantPortalApplication\MerchantPortalApplicationInterface
     */
    public function createMerchantPortalApplication(): MerchantPortalApplicationInterface
    {
        return new MerchantPortalApplication($this->createServiceContainer(), $this->getMerchantPortalApplicationPlugins());
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function createServiceContainer(): ContainerInterface
    {
        return new ContainerProxy(['logger' => null, 'debug' => $this->getConfig()->isDebugModeEnabled(), 'charset' => 'UTF-8']);
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    public function getMerchantPortalApplicationPlugins(): array
    {
        return $this->getProvidedDependency(MerchantPortalApplicationDependencyProvider::PLUGINS_MERCHANT_PORTAL_APPLICATION);
    }
}
