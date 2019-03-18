<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper;
use Spryker\Client\ProductBundle\QuoteChangeRequestExpander\QuoteChangeRequestExpander;
use Spryker\Client\ProductBundle\QuoteChangeRequestExpander\QuoteChangeRequestExpanderInterface;
use Spryker\Client\ProductBundle\QuoteItemFinder\BundleProductQuoteItemFinder;
use Spryker\Client\ProductBundle\QuoteItemFinder\BundleProductQuoteItemFinderInterface;
use Spryker\Client\ProductBundle\QuoteItemFinder\QuoteBundleItemsFinder;
use Spryker\Client\ProductBundle\QuoteItemFinder\QuoteBundleItemsFinderInterface;
use Spryker\Zed\ProductBundle\Dependency\Service\ProductBundleToUtilQuantityServiceInterface;

class ProductBundleFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouperInterface
     */
    public function createProductBundleGrouper()
    {
        return new ProductBundleGrouper($this->getUtilQuantityService());
    }

    /**
     * @return \Spryker\Client\ProductBundle\QuoteChangeRequestExpander\QuoteChangeRequestExpanderInterface
     */
    public function createQuoteChangeRequestExpander(): QuoteChangeRequestExpanderInterface
    {
        return new QuoteChangeRequestExpander($this->getUtilQuantityService());
    }

    /**
     * @return \Spryker\Client\ProductBundle\QuoteItemFinder\BundleProductQuoteItemFinderInterface
     */
    public function createBundleProductQuoteItemFinder(): BundleProductQuoteItemFinderInterface
    {
        return new BundleProductQuoteItemFinder($this->getUtilQuantityService());
    }

    /**
     * @return \Spryker\Client\ProductBundle\QuoteItemFinder\QuoteBundleItemsFinderInterface
     */
    public function createQuoteBundleItemsFinder(): QuoteBundleItemsFinderInterface
    {
        return new QuoteBundleItemsFinder();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Service\ProductBundleToUtilQuantityServiceInterface
     */
    public function getUtilQuantityService(): ProductBundleToUtilQuantityServiceInterface
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::SERVICE_UTIL_QUANTITY);
    }
}
