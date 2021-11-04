<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProductOptionStorage\Business\Filter\MerchantProductOptionFilter;
use Spryker\Zed\MerchantProductOptionStorage\Business\Filter\MerchantProductOptionFilterInterface;
use Spryker\Zed\MerchantProductOptionStorage\Business\Writer\MerchantProductOptionStorageWriter;
use Spryker\Zed\MerchantProductOptionStorage\Business\Writer\MerchantProductOptionStorageWriterInterface;
use Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToMerchantProductOptionFacadeInterface;
use Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToProductOptionStorageFacadeInterface;
use Spryker\Zed\MerchantProductOptionStorage\MerchantProductOptionStorageDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductOptionStorage\MerchantProductOptionStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOptionStorage\Persistence\MerchantProductOptionStorageRepositoryInterface getRepository()
 */
class MerchantProductOptionStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOptionStorage\Business\Filter\MerchantProductOptionFilterInterface
     */
    public function createMerchantProductOptionFilter(): MerchantProductOptionFilterInterface
    {
        return new MerchantProductOptionFilter(
            $this->getRepository(),
            $this->getMerchantProductOptionFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductOptionStorage\Business\Writer\MerchantProductOptionStorageWriterInterface
     */
    public function createMerchantProductOptionStorageWriter(): MerchantProductOptionStorageWriterInterface
    {
        return new MerchantProductOptionStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getRepository(),
            $this->getProductOptionStorageFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): MerchantProductOptionStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOptionStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToProductOptionStorageFacadeInterface
     */
    public function getProductOptionStorageFacade(): MerchantProductOptionStorageToProductOptionStorageFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOptionStorageDependencyProvider::FACADE_PRODUCT_OPTION_STORAGE);
    }

    /**
     * @return \Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToMerchantProductOptionFacadeInterface
     */
    public function getMerchantProductOptionFacade(): MerchantProductOptionStorageToMerchantProductOptionFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOptionStorageDependencyProvider::FACADE_MERCHANT_PRODUCT_OPTION);
    }
}
