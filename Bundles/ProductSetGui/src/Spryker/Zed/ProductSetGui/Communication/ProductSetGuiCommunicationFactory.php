<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSetGui\Communication\Form\CreateProductSetFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\DataMapper\CreateFormDataToTransferMapper;
use Spryker\Zed\ProductSetGui\Communication\Form\DataMapper\ReorderFormDataToTransferMapper;
use Spryker\Zed\ProductSetGui\Communication\Form\DataMapper\UpdateFormDataToTransferMapper;
use Spryker\Zed\ProductSetGui\Communication\Form\DataProvider\CreateFormDataProvider;
use Spryker\Zed\ProductSetGui\Communication\Form\DataProvider\ReorderProductSetsFormDataProvider;
use Spryker\Zed\ProductSetGui\Communication\Form\DataProvider\UpdateFormDataProvider;
use Spryker\Zed\ProductSetGui\Communication\Form\ReorderProductSetsFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\UpdateProductSetFormType;
use Spryker\Zed\ProductSetGui\Communication\Table\Helper\ProductAbstractTableHelper;
use Spryker\Zed\ProductSetGui\Communication\Table\ProductAbstractSetUpdateTable;
use Spryker\Zed\ProductSetGui\Communication\Table\ProductAbstractSetViewTable;
use Spryker\Zed\ProductSetGui\Communication\Table\ProductSetReorderTable;
use Spryker\Zed\ProductSetGui\Communication\Table\ProductSetTable;
use Spryker\Zed\ProductSetGui\Communication\Table\ProductTable;
use Spryker\Zed\ProductSetGui\Communication\Tabs\ProductSetFormTabs;
use Spryker\Zed\ProductSetGui\ProductSetGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSetGui\ProductSetGuiConfig getConfig()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 */
class ProductSetGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Form\DataProvider\CreateFormDataProvider
     */
    public function createCreateFormDataProvider()
    {
        return new CreateFormDataProvider($this->getLocaleFacade(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Form\DataProvider\UpdateFormDataProvider
     */
    public function createUpdateFormDataProvider()
    {
        return new UpdateFormDataProvider($this->getProductSetFacade(), $this->getLocaleFacade(), $this->getConfig());
    }

    /**
     * @deprecated Use {@link getProductSetForm()} instead.
     *
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCreateProductSetForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create($this->createCreateProductSetFormType(), $data, $options);
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCreateProductSetForm(array $data = [], array $options = [])
    {
        return $this->createCreateProductSetForm($data, $options);
    }

    /**
     * @deprecated Use {@link getUpdateProductSetForm()} instead.
     *
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createUpdateProductSetForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create($this->createUpdateProductSetFormType(), $data, $options);
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getUpdateProductSetForm(array $data = [], array $options = [])
    {
        return $this->createUpdateProductSetForm($data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Form\DataMapper\CreateFormDataToTransferMapper
     */
    public function createCreateFormDataToTransferMapper()
    {
        return new CreateFormDataToTransferMapper($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Form\DataMapper\UpdateFormDataToTransferMapper
     */
    public function createUpdateFormDataToTransferMapper()
    {
        return new UpdateFormDataToTransferMapper($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Form\DataMapper\ReorderFormDataToTransferMapper
     */
    public function createReorderFormDataToTransferMapper()
    {
        return new ReorderFormDataToTransferMapper();
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\AbstractTabs
     */
    public function createProductSetFormTabs()
    {
        return new ProductSetFormTabs();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Spryker\Zed\ProductSetGui\Communication\Table\ProductSetTable
     */
    public function createProductSetTable(LocaleTransfer $localeTransfer)
    {
        return new ProductSetTable($this->getQueryContainer(), $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Spryker\Zed\ProductSetGui\Communication\Table\ProductSetReorderTable
     */
    public function createProductSetReorderTable(LocaleTransfer $localeTransfer)
    {
        return new ProductSetReorderTable($this->getQueryContainer(), $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int|null $idProductSet
     *
     * @return \Spryker\Zed\ProductSetGui\Communication\Table\ProductTable
     */
    public function createProductTable(LocaleTransfer $localeTransfer, $idProductSet = null)
    {
        return new ProductTable($this->getQueryContainer(), $this->createProductAbstractTableHelper(), $localeTransfer, $idProductSet);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idProductSet
     *
     * @return \Spryker\Zed\ProductSetGui\Communication\Table\ProductAbstractSetUpdateTable
     */
    public function createProductAbstractSetUpdateTable(LocaleTransfer $localeTransfer, $idProductSet)
    {
        return new ProductAbstractSetUpdateTable($this->getQueryContainer(), $this->createProductAbstractTableHelper(), $localeTransfer, $idProductSet);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idProductSet
     *
     * @return \Spryker\Zed\ProductSetGui\Communication\Table\ProductAbstractSetViewTable
     */
    public function createProductAbstractSetViewTable(LocaleTransfer $localeTransfer, $idProductSet)
    {
        return new ProductAbstractSetViewTable($this->getQueryContainer(), $this->createProductAbstractTableHelper(), $localeTransfer, $idProductSet);
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createCreateProductSetFormType()
    {
        return CreateProductSetFormType::class;
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createUpdateProductSetFormType()
    {
        return UpdateProductSetFormType::class;
    }

    /**
     * @deprecated Use `getReorderProductSetsForm` instead.
     *
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createReorderProductSetsForm(array $data = [], $options = [])
    {
        return $this->getFormFactory()->create($this->createReorderProductSetsFormType(), $data, $options);
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getReorderProductSetsForm(array $data = [], $options = [])
    {
        return $this->createReorderProductSetsForm($data, $options);
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createReorderProductSetsFormType()
    {
        return ReorderProductSetsFormType::class;
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Form\DataProvider\ReorderProductSetsFormDataProvider
     */
    public function createReorderProductSetsFormDataProvider()
    {
        return new ReorderProductSetsFormDataProvider($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Table\Helper\ProductAbstractTableHelperInterface
     */
    protected function createProductAbstractTableHelper()
    {
        return new ProductAbstractTableHelper(
            $this->getProductImageFacade(),
            $this->getPriceProductFacade(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductSetInterface
     */
    public function getProductSetFacade()
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_PRODUCT_SET);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToUrlInterface
     */
    public function getUrlFacade()
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Service\ProductSetGuiToUtilEncodingInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductImageInterface
     */
    protected function getProductImageFacade()
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToPriceProductFacadeInterface
     */
    protected function getPriceProductFacade()
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\QueryContainer\ProductSetGuiToProductSetInterface
     */
    protected function getProductSetQueryContainer()
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_SET);
    }
}
