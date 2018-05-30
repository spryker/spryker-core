<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductReviewSearch\ProductReviewSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductReviewSearch\ProductReviewSearchConfig getConfig()
 */
class ProductReviewSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductReviewSearch\Dependency\Facade\ProductReviewSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductReviewSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ProductReviewSearch\Dependency\Facade\ProductReviewSearchToProductPageSearchFacadeInterface
     */
    public function getProductPageSearchFacade()
    {
        return $this->getProvidedDependency(ProductReviewSearchDependencyProvider::FACADE_PRODUCT_PAGE_SEARCH);
    }
}
