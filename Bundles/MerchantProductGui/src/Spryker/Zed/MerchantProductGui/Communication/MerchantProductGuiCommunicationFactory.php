<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProductGui\Dependency\Facade\MerchantProductGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantProductGui\Dependency\Facade\MerchantProductGuiToMerchantProductFacadeInterface;
use Spryker\Zed\MerchantProductGui\MerchantProductGuiDependencyProvider;

class MerchantProductGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(MerchantProductGuiDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->getApplication()['request'];
    }

    /**
     * @return \Spryker\Zed\MerchantProductGui\Dependency\Facade\MerchantProductGuiToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantProductGuiToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductGuiDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\MerchantProductGui\Dependency\Facade\MerchantProductGuiToMerchantProductFacadeInterface
     */
    public function getMerchantProductFacade(): MerchantProductGuiToMerchantProductFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductGuiDependencyProvider::FACADE_MERCHANT_PRODUCT);
    }
}
