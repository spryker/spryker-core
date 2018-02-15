<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductReviewSearch\Business\Search\ProductReviewSearchWriter;
use Spryker\Zed\ProductReviewSearch\ProductReviewSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductReviewSearch\ProductReviewSearchConfig getConfig()
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainerInterface getQueryContainer()
 */
class ProductReviewSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductReviewSearch\Business\Search\ProductReviewSearchWriterInterface
     */
    public function createProductReviewWriter()
    {
        return new ProductReviewSearchWriter(
            $this->getQueryContainer(),
            $this->getUtilEncoding(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(ProductReviewSearchDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\ProductReviewSearch\Dependency\Service\ProductReviewSearchToUtilEncodingInterface
     */
    protected function getUtilEncoding()
    {
        return $this->getProvidedDependency(ProductReviewSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
