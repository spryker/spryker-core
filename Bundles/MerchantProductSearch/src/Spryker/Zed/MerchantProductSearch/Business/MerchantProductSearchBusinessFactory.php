<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProductSearch\Business\Writer\MerchantProductSearchWriter;
use Spryker\Zed\MerchantProductSearch\Business\Writer\MerchantProductSearchWriterInterface;
use Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToProductPageSearchFacadeInterface;
use Spryker\Zed\MerchantProductSearch\MerchantProductSearchDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductSearch\MerchantProductSearchConfig getConfig()
 */
class MerchantProductSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductSearch\Business\Writer\MerchantProductSearchWriterInterface
     */
    public function createMerchantProductSearchWriter(): MerchantProductSearchWriterInterface
    {
        return new MerchantProductSearchWriter(
            $this->getEventBehaviorFacade(),
            $this->getProductPageSearchFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): MerchantProductSearchToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToProductPageSearchFacadeInterface
     */
    public function getProductPageSearchFacade(): MerchantProductSearchToProductPageSearchFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductSearchDependencyProvider::FACADE_PRODUCT_PAGE_SEARCH);
    }
}
