<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductReview\Communication\Controller\Mapper\CustomerReviewSubmitMapper;
use Spryker\Zed\ProductReview\ProductReviewDependencyProvider;

/**
 * @method \Spryker\Zed\ProductReview\ProductReviewConfig getConfig()
 * @method \Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductReview\Business\ProductReviewFacadeInterface getFacade()
 */
class ProductReviewCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductReview\Communication\Controller\Mapper\CustomerReviewSubmitMapperInterface
     */
    public function createCustomerReviewSubmitMapper()
    {
        return new CustomerReviewSubmitMapper($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::FACADE_LOCALE);
    }
}
