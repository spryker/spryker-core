<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferShipmentType\Business\Creator\ProductOfferShipmentTypeCreator;
use Spryker\Zed\ProductOfferShipmentType\Business\Creator\ProductOfferShipmentTypeCreatorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferExpander;
use Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferExpanderInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferProductOfferShipmentTypeCollectionExpander;
use Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferProductOfferShipmentTypeCollectionExpanderInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Expander\ShipmentTypeProductOfferShipmentTypeCollectionExpander;
use Spryker\Zed\ProductOfferShipmentType\Business\Expander\ShipmentTypeProductOfferShipmentTypeCollectionExpanderInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractor;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Filter\ProductOfferProductOfferShipmentTypeCollectionFilter;
use Spryker\Zed\ProductOfferShipmentType\Business\Filter\ProductOfferProductOfferShipmentTypeCollectionFilterInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Filter\ShipmentTypeProductOfferShipmentTypeCollectionFilter;
use Spryker\Zed\ProductOfferShipmentType\Business\Filter\ShipmentTypeProductOfferShipmentTypeCollectionFilterInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ProductOfferIndexer;
use Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ProductOfferIndexerInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ShipmentTypeIndexer;
use Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ShipmentTypeIndexerInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferReader;
use Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferShipmentTypeReader;
use Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferShipmentTypeReaderInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReader;
use Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Updater\ProductOfferShipmentTypeUpdater;
use Spryker\Zed\ProductOfferShipmentType\Business\Updater\ProductOfferShipmentTypeUpdaterInterface;
use Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToShipmentTypeFacadeInterface;
use Spryker\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface getRepository()
 */
class ProductOfferShipmentTypeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferExpanderInterface
     */
    public function createProductOfferExpander(): ProductOfferExpanderInterface
    {
        return new ProductOfferExpander(
            $this->getRepository(),
            $this->getShipmentTypeFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Creator\ProductOfferShipmentTypeCreatorInterface
     */
    public function createProductOfferShipmentTypeCreator(): ProductOfferShipmentTypeCreatorInterface
    {
        return new ProductOfferShipmentTypeCreator(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Updater\ProductOfferShipmentTypeUpdaterInterface
     */
    public function createProductOfferShipmentTypeUpdater(): ProductOfferShipmentTypeUpdaterInterface
    {
        return new ProductOfferShipmentTypeUpdater(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createShipmentTypeExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferShipmentTypeReaderInterface
     */
    public function createProductOfferShipmentTypeReader(): ProductOfferShipmentTypeReaderInterface
    {
        return new ProductOfferShipmentTypeReader(
            $this->getConfig(),
            $this->getRepository(),
            $this->createProductOfferReader(),
            $this->createShipmentTypeReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferReaderInterface
     */
    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader(
            $this->createProductOfferProductOfferShipmentTypeCollectionFilter(),
            $this->createProductOfferProductOfferShipmentTypeCollectionExpander(),
            $this->getProductOfferFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->createShipmentTypeProductOfferShipmentTypeCollectionFilter(),
            $this->createShipmentTypeProductOfferShipmentTypeCollectionExpander(),
            $this->createShipmentTypeExtractor(),
            $this->getShipmentTypeFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Filter\ProductOfferProductOfferShipmentTypeCollectionFilterInterface
     */
    public function createProductOfferProductOfferShipmentTypeCollectionFilter(): ProductOfferProductOfferShipmentTypeCollectionFilterInterface
    {
        return new ProductOfferProductOfferShipmentTypeCollectionFilter(
            $this->createProductOfferIndexer(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Filter\ShipmentTypeProductOfferShipmentTypeCollectionFilterInterface
     */
    public function createShipmentTypeProductOfferShipmentTypeCollectionFilter(): ShipmentTypeProductOfferShipmentTypeCollectionFilterInterface
    {
        return new ShipmentTypeProductOfferShipmentTypeCollectionFilter(
            $this->createShipmentTypeIndexer(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferProductOfferShipmentTypeCollectionExpanderInterface
     */
    public function createProductOfferProductOfferShipmentTypeCollectionExpander(): ProductOfferProductOfferShipmentTypeCollectionExpanderInterface
    {
        return new ProductOfferProductOfferShipmentTypeCollectionExpander(
            $this->createProductOfferIndexer(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ShipmentTypeProductOfferShipmentTypeCollectionExpanderInterface
     */
    public function createShipmentTypeProductOfferShipmentTypeCollectionExpander(): ShipmentTypeProductOfferShipmentTypeCollectionExpanderInterface
    {
        return new ShipmentTypeProductOfferShipmentTypeCollectionExpander(
            $this->createShipmentTypeIndexer(),
            $this->createShipmentTypeExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ProductOfferIndexerInterface
     */
    public function createProductOfferIndexer(): ProductOfferIndexerInterface
    {
        return new ProductOfferIndexer();
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ShipmentTypeIndexerInterface
     */
    public function createShipmentTypeIndexer(): ShipmentTypeIndexerInterface
    {
        return new ShipmentTypeIndexer();
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface
     */
    public function createShipmentTypeExtractor(): ShipmentTypeExtractorInterface
    {
        return new ShipmentTypeExtractor();
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToShipmentTypeFacadeInterface
     */
    public function getShipmentTypeFacade(): ProductOfferShipmentTypeToShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferShipmentTypeDependencyProvider::FACADE_SHIPMENT_TYPE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferShipmentTypeToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferShipmentTypeDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
