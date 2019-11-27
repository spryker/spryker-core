<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityGui\AvailabilityGuiDependencyProvider;
use Spryker\Zed\AvailabilityGui\Communication\Form\AvailabilityStockForm;
use Spryker\Zed\AvailabilityGui\Communication\Form\DataProvider\AvailabilityStockFormDataProvider;
use Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelper;
use Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelperInterface;
use Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityAbstractTable;
use Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityTable;
use Spryker\Zed\AvailabilityGui\Communication\Table\BundledProductAvailabilityTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\AvailabilityGui\AvailabilityGuiConfig getConfig()
 */
class AvailabilityGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityAbstractTable
     */
    public function createAvailabilityAbstractTable($idLocale, $idStore)
    {
        return new AvailabilityAbstractTable(
            $this->createProductAvailabilityHelper(),
            $this->getStoreFacade(),
            $idStore,
            $idLocale
        );
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityTable
     */
    public function createAvailabilityTable($idProductAbstract, $idLocale, $idStore)
    {
        return new AvailabilityTable(
            $this->createProductAvailabilityHelper(),
            $this->getStoreFacade(),
            $idProductAbstract,
            $idLocale,
            $idStore
        );
    }

    /**
     * @param int $idLocale
     * @param int $idStore
     * @param int $idAbstractProductBundle
     * @param int $idBundleProductAbstract
     *
     * @return \Spryker\Zed\AvailabilityGui\Communication\Table\BundledProductAvailabilityTable
     */
    public function createBundledProductAvailabilityTable(
        $idLocale,
        $idStore,
        $idAbstractProductBundle,
        $idBundleProductAbstract
    ) {
        return new BundledProductAvailabilityTable(
            $this->createProductAvailabilityHelper(),
            $this->getProductBundleQueryContainer(),
            $this->getStoreFacade(),
            $idLocale,
            $idStore,
            $idAbstractProductBundle,
            $idBundleProductAbstract
        );
    }

    /**
     * @param int $idProduct
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAvailabilityStockForm($idProduct, $sku, StoreTransfer $storeTransfer)
    {
        $availabilityForm = new AvailabilityStockForm();

        $availabilityGuiStockFormDataProvider = $this->createAvailabilityGuiStockFormDataProvider($storeTransfer);

        return $this->getFormFactory()->create(
            AvailabilityStockForm::class,
            $availabilityGuiStockFormDataProvider->getData($idProduct, $sku),
            $availabilityGuiStockFormDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelperInterface
     */
    public function createProductAvailabilityHelper(): AvailabilityHelperInterface
    {
        return new AvailabilityHelper(
            $this->getAvailabilityQueryContainer(),
            $this->getProductBundleQueryContainer(),
            $this->getStoreFacade(),
            $this->getStockFacade(),
            $this->getOmsFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\Zed\AvailabilityGui\Communication\Form\DataProvider\AvailabilityStockFormDataProvider
     */
    public function createAvailabilityGuiStockFormDataProvider(StoreTransfer $storeTransfer)
    {
        return new AvailabilityStockFormDataProvider($this->getStockFacade(), $storeTransfer);
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToLocaleInterface
     */
    public function getLocalFacade()
    {
        return $this->getProvidedDependency(AvailabilityGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface
     */
    public function getStockFacade()
    {
        return $this->getProvidedDependency(AvailabilityGuiDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToAvailabilityQueryContainerInterface
     */
    public function getAvailabilityQueryContainer()
    {
        return $this->getProvidedDependency(AvailabilityGuiDependencyProvider::QUERY_CONTAINER_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface
     */
    public function getProductBundleQueryContainer()
    {
        return $this->getProvidedDependency(AvailabilityGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_BUNDLE);
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    public function getStoreFacade()
    {
        return $this->getProvidedDependency(AvailabilityGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface
     */
    protected function getOmsFacade()
    {
        return $this->getProvidedDependency(AvailabilityGuiDependencyProvider::FACADE_OMS);
    }
}
