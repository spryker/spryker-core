<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Communication;

use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferGui\Communication\Form\DataProvider\TableFilterFormDataProvider;
use Spryker\Zed\ProductOfferGui\Communication\Form\TableFilterForm;
use Spryker\Zed\ProductOfferGui\Communication\Table\ProductOfferTable;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductFacadeInterface;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToStoreFacadeInterface;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferGui\ProductOfferGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
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
            $this->getProductOfferFacade(),
            $this->getRepository(),
            $this->getProductOfferTableExpanderPlugins(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     * @param array<mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTableFilterForm(
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer,
        array $formOptions = []
    ): FormInterface {
        return $this->getFormFactory()->create(TableFilterForm::class, $productOfferTableCriteriaTransfer, $formOptions);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGui\Communication\Form\DataProvider\TableFilterFormDataProvider
     */
    public function createTableFilterFormDataProvider(): TableFilterFormDataProvider
    {
        return new TableFilterFormDataProvider(
            $this->getProductOfferFacade(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery<mixed>
     */
    public function getProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::PROPEL_QUERY_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductOfferGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferGuiToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::FACADE_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return array<\Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferListActionViewDataExpanderPluginInterface>
     */
    public function getProductOfferListActionViewDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::PLUGINS_PRODUCT_OFFER_LIST_ACTION_VIEW_DATA_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferTableExpanderPluginInterface>
     */
    protected function getProductOfferTableExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::PLUGINS_PRODUCT_OFFER_TABLE_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductOfferGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return array<\Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferViewSectionPluginInterface>
     */
    public function getProductOfferViewSectionPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::PLUGINS_PRODUCT_OFFER_VIEW_SECTION);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): ProductOfferGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiDependencyProvider::FACADE_TRANSLATOR);
    }
}
