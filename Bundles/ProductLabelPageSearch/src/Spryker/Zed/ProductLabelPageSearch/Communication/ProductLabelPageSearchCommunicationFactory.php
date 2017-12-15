<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelPageSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductLabelPageSearch\Dependency\Facade\ProductLabelPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductLabelPageSearch\Dependency\Service\ProductLabelPageSearchToUtilSanitizeServiceInterface;
use Spryker\Zed\ProductLabelPageSearch\ProductLabelPageSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelPageSearch\ProductLabelPageSearchConfig getConfig()
 */
class ProductLabelPageSearchCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductLabelPageSearchToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(ProductLabelPageSearchDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return ProductLabelPageSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductLabelPageSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ProductLabelPageSearch\Dependency\Facade\ProductLabelPageSearchToProductLabelInterface
     */
    public function getProductLabelFacade()
    {
        return $this->getProvidedDependency(ProductLabelPageSearchDependencyProvider::FACADE_PRODUCT_LABEL);
    }

    /**
     * @return \Spryker\Zed\ProductLabelPageSearch\Dependency\Facade\ProductLabelPageSearchToProductPageSearchInterface
     */
    public function getProductPageSearchFacade()
    {
        return $this->getProvidedDependency(ProductLabelPageSearchDependencyProvider::FACADE_PRODUCT_PAGE_SEARCH);
    }

}
