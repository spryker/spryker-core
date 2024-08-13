<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAppMerchantPortalGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantAppMerchantPortalGui\Dependency\Facade\MerchantAppMerchantPortalGuiToMerchantAppFacadeInterface;
use Spryker\Zed\MerchantAppMerchantPortalGui\Dependency\Facade\MerchantAppMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantAppMerchantPortalGui\MerchantAppMerchantPortalGuiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantAppMerchantPortalGui\MerchantAppMerchantPortalGuiConfig getConfig()
 */
class MerchantAppMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantAppMerchantPortalGui\Dependency\Facade\MerchantAppMerchantPortalGuiToMerchantAppFacadeInterface
     */
    public function getMerchantAppFacade(): MerchantAppMerchantPortalGuiToMerchantAppFacadeInterface
    {
        return $this->getProvidedDependency(MerchantAppMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_APP);
    }

    /**
     * @return \Spryker\Zed\MerchantAppMerchantPortalGui\Dependency\Facade\MerchantAppMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantAppMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantAppMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }
}
