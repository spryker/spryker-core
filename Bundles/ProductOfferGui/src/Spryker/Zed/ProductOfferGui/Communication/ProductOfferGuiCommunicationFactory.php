<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferGui\Communication\Helper\ProductTypeHelper;
use Spryker\Zed\ProductOfferGui\Communication\Helper\ProductTypeHelperInterface;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductFacadeInterface;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferGui\ProductOfferGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferGui\ProductOfferGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiRepositoryInterface getRepository()
 */
class ProductOfferGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferGui\Communication\Helper\ProductTypeHelperInterface
     */
    public function createProductTypeHelper(): ProductTypeHelperInterface
    {
        return new ProductTypeHelper($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferGuiToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::FACADE_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductOfferGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductOfferGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferViewSectionPluginInterface[]
     */
    public function getProductOfferViewSectionPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::PLUGINS_PRODUCT_OFFER_VIEW_SECTION);
    }
}
