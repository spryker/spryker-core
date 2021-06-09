<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProductOptionGui\Communication\Expander\MerchantProductOptionOptionQueryCriteriaExpander;
use Spryker\Zed\MerchantProductOptionGui\Communication\Expander\MerchantProductOptionQueryCriteriaExpanderInterface;
use Spryker\Zed\MerchantProductOptionGui\MerchantProductOptionGuiDependencyProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \Spryker\Zed\MerchantProductOptionGui\Persistence\MerchantProductOptionGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOptionGui\MerchantProductOptionGuiConfig getConfig()
 */
class MerchantProductOptionGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOptionGui\Communication\Expander\MerchantProductOptionQueryCriteriaExpanderInterface
     */
    public function createMerchantProductQueryCriteriaExpander(): MerchantProductOptionQueryCriteriaExpanderInterface
    {
        return new MerchantProductOptionOptionQueryCriteriaExpander(
            $this->getRepository(),
            $this->getRequest()
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->getRequestStack()->getCurrentRequest();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(MerchantProductOptionGuiDependencyProvider::SERVICE_REQUEST_STACK);
    }
}
