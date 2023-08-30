<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointAvailability\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Expander\SellableItemsResponseExpander;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Expander\SellableItemsResponseExpanderInterface;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Extractor\SellableItemRequestExtractor;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Extractor\SellableItemRequestExtractorInterface;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Filter\SellableItemRequestFilter;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Filter\SellableItemRequestFilterInterface;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferReader;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferServicePointAvailabilityReader;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferServicePointAvailabilityReaderInterface;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferServiceReader;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferServiceReaderInterface;
use Spryker\Zed\ProductOfferServicePointAvailability\Dependency\Facade\ProductOfferServicePointAvailabilityToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferServicePointAvailability\Dependency\Facade\ProductOfferServicePointAvailabilityToProductOfferServicePointFacadeInterface;
use Spryker\Zed\ProductOfferServicePointAvailability\ProductOfferServicePointAvailabilityDependencyProvider;

class ProductOfferServicePointAvailabilityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferServicePointAvailabilityReaderInterface
     */
    public function createProductOfferServicePointAvailabilityReader(): ProductOfferServicePointAvailabilityReaderInterface
    {
        return new ProductOfferServicePointAvailabilityReader(
            $this->createProductOfferReader(),
            $this->createSellableItemRequestExtractor(),
            $this->createProductOfferServiceReader(),
            $this->createSellableItemsResponseExpander(),
            $this->createSellableItemRequestFilter(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointAvailability\Business\Filter\SellableItemRequestFilterInterface
     */
    public function createSellableItemRequestFilter(): SellableItemRequestFilterInterface
    {
        return new SellableItemRequestFilter();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointAvailability\Business\Expander\SellableItemsResponseExpanderInterface
     */
    public function createSellableItemsResponseExpander(): SellableItemsResponseExpanderInterface
    {
        return new SellableItemsResponseExpander();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointAvailability\Business\Extractor\SellableItemRequestExtractorInterface
     */
    public function createSellableItemRequestExtractor(): SellableItemRequestExtractorInterface
    {
        return new SellableItemRequestExtractor();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferServiceReaderInterface
     */
    public function createProductOfferServiceReader(): ProductOfferServiceReaderInterface
    {
        return new ProductOfferServiceReader(
            $this->getProductOfferServicePointFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferReaderInterface
     */
    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader(
            $this->getProductOfferFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointAvailability\Dependency\Facade\ProductOfferServicePointAvailabilityToProductOfferServicePointFacadeInterface
     */
    public function getProductOfferServicePointFacade(): ProductOfferServicePointAvailabilityToProductOfferServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityDependencyProvider::FACADE_PRODUCT_OFFER_SERVICE_POINT);
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointAvailability\Dependency\Facade\ProductOfferServicePointAvailabilityToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferServicePointAvailabilityToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
