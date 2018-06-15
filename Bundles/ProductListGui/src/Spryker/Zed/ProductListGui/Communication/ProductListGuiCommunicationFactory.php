<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductListGui\Communication\DataProvider\CategoriesDataProvider;
use Spryker\Zed\ProductListGui\Communication\DataProvider\ProductListDataProvider;
use Spryker\Zed\ProductListGui\Communication\DataProvider\ProductListProductConcreteRelationDataProvider;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListForm;
use Spryker\Zed\ProductListGui\Communication\Table\ProductConcreteTable;
use Spryker\Zed\ProductListGui\Communication\Table\ProductListTable;
use Spryker\Zed\ProductListGui\Communication\Tabs\ProductConcreteTabs;
use Spryker\Zed\ProductListGui\Communication\Tabs\ProductListTabs;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface;
use Spryker\Zed\ProductListGui\ProductListGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductListGui\Business\ProductListGuiFacadeInterface getFacade();
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 */
class ProductListGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Table\ProductListTable
     */
    public function createProductListTable(): ProductListTable
    {
        return new ProductListTable();
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Table\ProductConcreteTable
     */
    public function createProductConcreteTable(): ProductConcreteTable
    {
        return new ProductConcreteTable();
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Tabs\ProductListTabs
     */
    public function createProductListTabs(): ProductListTabs
    {
        return new ProductListTabs();
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Tabs\ProductConcreteTabs
     */
    public function createProductConcreteTabs(): ProductConcreteTabs
    {
        return new ProductConcreteTabs();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer|null $productListTransfer
     *
     * @return \Spryker\Zed\ProductListGui\Communication\Form\ProductListForm|\Symfony\Component\Form\FormInterface
     */
    public function getProductListForm(?ProductListTransfer $productListTransfer = null): FormInterface
    {
        $dataProvider = $this->createProductListDataProvider();

        return $this->getFormFactory()->create(
            ProductListForm::class,
            $dataProvider->getData($productListTransfer),
            $dataProvider->getOptions($productListTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\DataProvider\ProductListDataProvider
     */
    public function createProductListDataProvider(): ProductListDataProvider
    {
        return new ProductListDataProvider(
            $this->createCategoriesDataProvider(),
            $this->createProductConcreteDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\DataProvider\CategoriesDataProvider
     */
    public function createCategoriesDataProvider(): CategoriesDataProvider
    {
        return new CategoriesDataProvider(
            $this->getFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\DataProvider\ProductListProductConcreteRelationDataProvider
     */
    public function createProductConcreteDataProvider(): ProductListProductConcreteRelationDataProvider
    {
        return new ProductListProductConcreteRelationDataProvider(
            $this->getFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface
     */
    public function getProductListFacade(): ProductListGuiToProductListFacadeInterface
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::FACADE_PRODUCT_LIST);
    }
}
