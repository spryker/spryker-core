<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Communication;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferGui\Communication\Table\ProductOfferTable;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToLocaleInterface;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductOfferInterface;
use Spryker\Zed\ProductOfferGui\ProductOfferGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOfferGui\ProductOfferGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiRepositoryInterface getRepository()
 */
class ProductOfferGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferGui\Communication\Table\ProductOfferTable
     */
    public function createProductOfferTable(): ProductOfferTable
    {
        return new ProductOfferTable(
            $this->getProductOfferPropelQuery(),
            $this->getLocaleFacade(),
            $this->getRepository(),
            $this->getProductOfferTableExpanderPlugins()
        );
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    public function getProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::PROPEL_QUERY_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToLocaleInterface
     */
    public function getLocaleFacade(): ProductOfferGuiToLocaleInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductOfferInterface
     */
    public function getProductOfferFacade(): ProductOfferGuiToProductOfferInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::FACADE_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferListActionViewDataExpanderPluginInterface[]
     */
    public function getProductOfferListActionViewDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::PLUGINS_PRODUCT_OFFER_LIST_ACTION_VIEW_DATA_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferTableExpanderPluginInterface[]
     */
    protected function getProductOfferTableExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::PLUGINS_PRODUCT_OFFER_TABLE_EXPANDER);
    }
}
