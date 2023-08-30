<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeAvailability\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Expander\SellableItemsResponseExpander;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Expander\SellableItemsResponseExpanderInterface;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Extractor\SellableItemRequestExtractor;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Extractor\SellableItemRequestExtractorInterface;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Filter\SellableItemRequestFilter;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Filter\SellableItemRequestFilterInterface;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferReader;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferShipmentTypeAvailabilityReader;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferShipmentTypeAvailabilityReaderInterface;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferShipmentTypeReader;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferShipmentTypeReaderInterface;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade\ProductOfferShipmentTypeAvailabilityToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade\ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeInterface;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\ProductOfferShipmentTypeAvailabilityDependencyProvider;

class ProductOfferShipmentTypeAvailabilityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferShipmentTypeAvailabilityReaderInterface
     */
    public function createProductOfferShipmentTypeAvailabilityReader(): ProductOfferShipmentTypeAvailabilityReaderInterface
    {
        return new ProductOfferShipmentTypeAvailabilityReader(
            $this->createProductOfferReader(),
            $this->createSellableItemRequestExtractor(),
            $this->createProductOfferShipmentTypeReader(),
            $this->createSellableItemsResponseExpander(),
            $this->createSellableItemRequestFilter(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Filter\SellableItemRequestFilterInterface
     */
    public function createSellableItemRequestFilter(): SellableItemRequestFilterInterface
    {
        return new SellableItemRequestFilter();
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Expander\SellableItemsResponseExpanderInterface
     */
    public function createSellableItemsResponseExpander(): SellableItemsResponseExpanderInterface
    {
        return new SellableItemsResponseExpander();
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Extractor\SellableItemRequestExtractorInterface
     */
    public function createSellableItemRequestExtractor(): SellableItemRequestExtractorInterface
    {
        return new SellableItemRequestExtractor();
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferShipmentTypeReaderInterface
     */
    public function createProductOfferShipmentTypeReader(): ProductOfferShipmentTypeReaderInterface
    {
        return new ProductOfferShipmentTypeReader(
            $this->getProductOfferShipmentTypeFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferReaderInterface
     */
    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader(
            $this->getProductOfferFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade\ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeInterface
     */
    public function getProductOfferShipmentTypeFacade(): ProductOfferShipmentTypeAvailabilityToProductOfferShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferShipmentTypeAvailabilityDependencyProvider::FACADE_PRODUCT_OFFER_SHIPMENT_TYPE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentTypeAvailability\Dependency\Facade\ProductOfferShipmentTypeAvailabilityToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferShipmentTypeAvailabilityToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferShipmentTypeAvailabilityDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
