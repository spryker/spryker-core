<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductConfigurationsRestApi\Business\Comparator\ItemComparator;
use Spryker\Zed\ProductConfigurationsRestApi\Business\Comparator\ItemComparatorInterface;
use Spryker\Zed\ProductConfigurationsRestApi\Business\Mapper\ProductConfigurationMapper;
use Spryker\Zed\ProductConfigurationsRestApi\Business\Mapper\ProductConfigurationMapperInterface;
use Spryker\Zed\ProductConfigurationsRestApi\Business\Updater\QuoteItemUpdater;
use Spryker\Zed\ProductConfigurationsRestApi\Business\Updater\QuoteItemUpdaterInterface;
use Spryker\Zed\ProductConfigurationsRestApi\Dependency\Facade\ProductConfigurationsRestApiToPersistentCartFacadeInterface;
use Spryker\Zed\ProductConfigurationsRestApi\ProductConfigurationsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductConfigurationsRestApi\ProductConfigurationsRestApiConfig getConfig()
 */
class ProductConfigurationsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductConfigurationsRestApi\Business\Mapper\ProductConfigurationMapperInterface
     */
    public function createProductConfigurationMapper(): ProductConfigurationMapperInterface
    {
        return new ProductConfigurationMapper($this->createItemComparator());
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationsRestApi\Business\Updater\QuoteItemUpdaterInterface
     */
    public function createQuoteItemUpdater(): QuoteItemUpdaterInterface
    {
        return new QuoteItemUpdater(
            $this->createItemComparator(),
            $this->getPersistentCartFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationsRestApi\Business\Comparator\ItemComparatorInterface
     */
    public function createItemComparator(): ItemComparatorInterface
    {
        return new ItemComparator($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationsRestApi\Dependency\Facade\ProductConfigurationsRestApiToPersistentCartFacadeInterface
     */
    public function getPersistentCartFacade(): ProductConfigurationsRestApiToPersistentCartFacadeInterface
    {
        return $this->getProvidedDependency(ProductConfigurationsRestApiDependencyProvider::FACADE_PERSISTENT_CART);
    }
}
