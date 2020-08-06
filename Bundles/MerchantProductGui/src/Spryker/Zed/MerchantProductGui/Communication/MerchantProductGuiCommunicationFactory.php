<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProductGui\Communication\Expander\MerchantProductQueryCriteriaExpander;
use Spryker\Zed\MerchantProductGui\Communication\Expander\MerchantProductQueryCriteriaExpanderInterface;
use Spryker\Zed\MerchantProductGui\Communication\Expander\MerchantProductViewDataExpander;
use Spryker\Zed\MerchantProductGui\Communication\Expander\MerchantProductViewDataExpanderInterface;
use Spryker\Zed\MerchantProductGui\Dependency\Facade\MerchantProductGuiToMerchantProductFacadeInterface;
use Spryker\Zed\MerchantProductGui\MerchantProductGuiDependencyProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \Spryker\Zed\MerchantProductGui\Persistence\MerchantProductGuiRepositoryInterface getRepository()
 */
class MerchantProductGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductGui\Communication\Expander\MerchantProductQueryCriteriaExpanderInterface
     */
    public function createMerchantProductQueryCriteriaExpander(): MerchantProductQueryCriteriaExpanderInterface
    {
        return new MerchantProductQueryCriteriaExpander(
            $this->getRepository(),
            $this->getRequest()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductGui\Communication\Expander\MerchantProductViewDataExpanderInterface
     */
    public function createMerchantProductViewDataExpander(): MerchantProductViewDataExpanderInterface
    {
        return new MerchantProductViewDataExpander($this->getMerchantProductFacade());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(MerchantProductGuiDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): Request
    {
        return $this->getRequestStack()->getCurrentRequest();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(MerchantProductGuiDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \Spryker\Zed\MerchantProductGui\Dependency\Facade\MerchantProductGuiToMerchantProductFacadeInterface
     */
    public function getMerchantProductFacade(): MerchantProductGuiToMerchantProductFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductGuiDependencyProvider::FACADE_MERCHANT_PRODUCT);
    }
}
