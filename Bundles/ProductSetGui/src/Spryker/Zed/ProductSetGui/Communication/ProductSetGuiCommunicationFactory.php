<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSetGui\Communication\Form\ActivateProductSetForm;
use Spryker\Zed\ProductSetGui\Communication\Form\CreateProductSetFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\DataMapper\CreateFormDataToTransferMapper;
use Spryker\Zed\ProductSetGui\Communication\Form\DataMapper\ReorderFormDataToTransferMapper;
use Spryker\Zed\ProductSetGui\Communication\Form\DataMapper\UpdateFormDataToTransferMapper;
use Spryker\Zed\ProductSetGui\Communication\Form\DataProvider\CreateFormDataProvider;
use Spryker\Zed\ProductSetGui\Communication\Form\DataProvider\ReorderProductSetsFormDataProvider;
use Spryker\Zed\ProductSetGui\Communication\Form\DataProvider\UpdateFormDataProvider;
use Spryker\Zed\ProductSetGui\Communication\Form\DeactivateProductSetForm;
use Spryker\Zed\ProductSetGui\Communication\Form\DeleteProductSetForm;
use Spryker\Zed\ProductSetGui\Communication\Form\ReorderProductSetsFormType;
use Spryker\Zed\ProductSetGui\Communication\Form\UpdateProductSetFormType;
use Spryker\Zed\ProductSetGui\Communication\Table\Helper\ProductAbstractTableHelper;
use Spryker\Zed\ProductSetGui\Communication\Table\Helper\ProductAbstractTableHelperInterface;
use Spryker\Zed\ProductSetGui\Communication\Table\ProductAbstractSetUpdateTable;
use Spryker\Zed\ProductSetGui\Communication\Table\ProductAbstractSetViewTable;
use Spryker\Zed\ProductSetGui\Communication\Table\ProductSetReorderTable;
use Spryker\Zed\ProductSetGui\Communication\Table\ProductSetTable;
use Spryker\Zed\ProductSetGui\Communication\Table\ProductTable;
use Spryker\Zed\ProductSetGui\Communication\Tabs\ProductSetFormTabs;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleInterface;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToMoneyInterface;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductImageInterface;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductSetInterface;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToStoreFacadeInterface;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToUrlInterface;
use Spryker\Zed\ProductSetGui\Dependency\QueryContainer\ProductSetGuiToProductSetInterface as QueryContainerProductSetGuiToProductSetInterface;
use Spryker\Zed\ProductSetGui\Dependency\Service\ProductSetGuiToUtilEncodingInterface;
use Spryker\Zed\ProductSetGui\ProductSetGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductSetGui\ProductSetGuiConfig getConfig()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 */
class ProductSetGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Form\DataProvider\CreateFormDataProvider
     */
    public function createCreateFormDataProvider(): CreateFormDataProvider
    {
        return new CreateFormDataProvider($this->getLocaleFacade(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Form\DataProvider\UpdateFormDataProvider
     */
    public function createUpdateFormDataProvider(): UpdateFormDataProvider
    {
        return new UpdateFormDataProvider($this->getProductSetFacade(), $this->getLocaleFacade(), $this->getConfig());
    }

    /**
     * @deprecated Use {@link getCreateProductSetForm()} instead.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCreateProductSetForm(array $data = [], array $options = []): FormInterface
    {
        return $this->getFormFactory()->create($this->createCreateProductSetFormType(), $data, $options);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createActivateProductSetForm(): FormInterface
    {
        return $this->getFormFactory()->create(ActivateProductSetForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeactivateProductSetForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeactivateProductSetForm::class);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCreateProductSetForm(array $data = [], array $options = []): FormInterface
    {
        return $this->createCreateProductSetForm($data, $options);
    }

    /**
     * @deprecated Use {@link getUpdateProductSetForm()} instead.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createUpdateProductSetForm(array $data = [], array $options = []): FormInterface
    {
        return $this->getFormFactory()->create($this->createUpdateProductSetFormType(), $data, $options);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getUpdateProductSetForm(array $data = [], array $options = []): FormInterface
    {
        return $this->createUpdateProductSetForm($data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Form\DataMapper\CreateFormDataToTransferMapper
     */
    public function createCreateFormDataToTransferMapper(): CreateFormDataToTransferMapper
    {
        return new CreateFormDataToTransferMapper($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Form\DataMapper\UpdateFormDataToTransferMapper
     */
    public function createUpdateFormDataToTransferMapper(): UpdateFormDataToTransferMapper
    {
        return new UpdateFormDataToTransferMapper($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Form\DataMapper\ReorderFormDataToTransferMapper
     */
    public function createReorderFormDataToTransferMapper(): ReorderFormDataToTransferMapper
    {
        return new ReorderFormDataToTransferMapper();
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Tabs\ProductSetFormTabs
     */
    public function createProductSetFormTabs(): ProductSetFormTabs
    {
        return new ProductSetFormTabs();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Spryker\Zed\ProductSetGui\Communication\Table\ProductSetTable
     */
    public function createProductSetTable(LocaleTransfer $localeTransfer): ProductSetTable
    {
        return new ProductSetTable($this->getQueryContainer(), $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Spryker\Zed\ProductSetGui\Communication\Table\ProductSetReorderTable
     */
    public function createProductSetReorderTable(LocaleTransfer $localeTransfer): ProductSetReorderTable
    {
        return new ProductSetReorderTable($this->getQueryContainer(), $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int|null $idProductSet
     *
     * @return \Spryker\Zed\ProductSetGui\Communication\Table\ProductTable
     */
    public function createProductTable(LocaleTransfer $localeTransfer, $idProductSet = null): ProductTable
    {
        return new ProductTable(
            $this->getQueryContainer(),
            $this->createProductAbstractTableHelper(),
            $localeTransfer,
            $this->getStoreFacade(),
            $idProductSet,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idProductSet
     *
     * @return \Spryker\Zed\ProductSetGui\Communication\Table\ProductAbstractSetUpdateTable
     */
    public function createProductAbstractSetUpdateTable(LocaleTransfer $localeTransfer, $idProductSet): ProductAbstractSetUpdateTable
    {
        return new ProductAbstractSetUpdateTable(
            $this->getQueryContainer(),
            $this->createProductAbstractTableHelper(),
            $this->getStoreFacade(),
            $localeTransfer,
            $idProductSet,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idProductSet
     *
     * @return \Spryker\Zed\ProductSetGui\Communication\Table\ProductAbstractSetViewTable
     */
    public function createProductAbstractSetViewTable(LocaleTransfer $localeTransfer, $idProductSet): ProductAbstractSetViewTable
    {
        return new ProductAbstractSetViewTable(
            $this->getQueryContainer(),
            $this->createProductAbstractTableHelper(),
            $this->getStoreFacade(),
            $localeTransfer,
            $idProductSet,
        );
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    public function createCreateProductSetFormType(): string
    {
        return CreateProductSetFormType::class;
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    public function createUpdateProductSetFormType(): string
    {
        return UpdateProductSetFormType::class;
    }

    /**
     * @deprecated Use {@link getReorderProductSetsForm()} instead.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createReorderProductSetsForm(array $data = [], $options = []): FormInterface
    {
        return $this->getFormFactory()->create($this->createReorderProductSetsFormType(), $data, $options);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getReorderProductSetsForm(array $data = [], $options = []): FormInterface
    {
        return $this->createReorderProductSetsForm($data, $options);
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    public function createReorderProductSetsFormType(): string
    {
        return ReorderProductSetsFormType::class;
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Form\DataProvider\ReorderProductSetsFormDataProvider
     */
    public function createReorderProductSetsFormDataProvider(): ReorderProductSetsFormDataProvider
    {
        return new ReorderProductSetsFormDataProvider($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Communication\Table\Helper\ProductAbstractTableHelperInterface
     */
    public function createProductAbstractTableHelper(): ProductAbstractTableHelperInterface
    {
        return new ProductAbstractTableHelper(
            $this->getProductImageFacade(),
            $this->getPriceProductFacade(),
            $this->getMoneyFacade(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteProductSetForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeleteProductSetForm::class, null, ['fields' => []]);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductSetInterface
     */
    public function getProductSetFacade(): ProductSetGuiToProductSetInterface
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_PRODUCT_SET);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleInterface
     */
    public function getLocaleFacade(): ProductSetGuiToLocaleInterface
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToUrlInterface
     */
    public function getUrlFacade(): ProductSetGuiToUrlInterface
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Service\ProductSetGuiToUtilEncodingInterface
     */
    public function getUtilEncodingService(): ProductSetGuiToUtilEncodingInterface
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductImageInterface
     */
    public function getProductImageFacade(): ProductSetGuiToProductImageInterface
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): ProductSetGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToMoneyInterface
     */
    public function getMoneyFacade(): ProductSetGuiToMoneyInterface
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\QueryContainer\ProductSetGuiToProductSetInterface
     */
    public function getProductSetQueryContainer(): QueryContainerProductSetGuiToProductSetInterface
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_SET);
    }

    /**
     * @return \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductSetGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductSetGuiDependencyProvider::FACADE_STORE);
    }
}
