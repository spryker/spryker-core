<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOffer\Business\Checker\ItemProductOfferChecker;
use Spryker\Zed\ProductOffer\Business\Checker\ItemProductOfferCheckerInterface;
use Spryker\Zed\ProductOffer\Business\Generator\ProductOfferReferenceGenerator;
use Spryker\Zed\ProductOffer\Business\Generator\ProductOfferReferenceGeneratorInterface;
use Spryker\Zed\ProductOffer\Business\InactiveProductOfferItemsFilter\InactiveProductOfferItemsFilter;
use Spryker\Zed\ProductOffer\Business\InactiveProductOfferItemsFilter\InactiveProductOfferItemsFilterInterface;
use Spryker\Zed\ProductOffer\Business\Reader\ProductOfferReader;
use Spryker\Zed\ProductOffer\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOffer\Business\Writer\ProductOfferWriter;
use Spryker\Zed\ProductOffer\Business\Writer\ProductOfferWriterInterface;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToMessengerFacadeInterface;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface;
use Spryker\Zed\ProductOffer\ProductOfferDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface getRepository()
 */
class ProductOfferBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOffer\Business\Writer\ProductOfferWriterInterface
     */
    public function createProductOfferWriter(): ProductOfferWriterInterface
    {
        return new ProductOfferWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createProductOfferReferenceGenerator(),
            $this->getProductOfferPostCreatePlugins(),
            $this->getProductOfferPostUpdatePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\InactiveProductOfferItemsFilter\InactiveProductOfferItemsFilterInterface
     */
    public function createInactiveProductOfferItemsFilter(): InactiveProductOfferItemsFilterInterface
    {
        return new InactiveProductOfferItemsFilter(
            $this->getRepository(),
            $this->getStoreFacade(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Checker\ItemProductOfferCheckerInterface
     */
    public function createItemProductOfferChecker(): ItemProductOfferCheckerInterface
    {
        return new ItemProductOfferChecker($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToMessengerFacadeInterface
     */
    public function getMessengerFacade(): ProductOfferToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Reader\ProductOfferReaderInterface
     */
    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader(
            $this->getRepository(),
            $this->getProductOfferExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Generator\ProductOfferReferenceGeneratorInterface
     */
    public function createProductOfferReferenceGenerator(): ProductOfferReferenceGeneratorInterface
    {
        return new ProductOfferReferenceGenerator($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostCreatePluginInterface[]
     */
    public function getProductOfferPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::PLUGINS_PRODUCT_OFFER_POST_CREATE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostUpdatePluginInterface[]
     */
    public function getProductOfferPostUpdatePlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::PLUGINS_PRODUCT_OFFER_POST_UPDATE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferExpanderPluginInterface[]
     */
    public function getProductOfferExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::PLUGINS_PRODUCT_OFFER_EXPANDER);
    }
}
