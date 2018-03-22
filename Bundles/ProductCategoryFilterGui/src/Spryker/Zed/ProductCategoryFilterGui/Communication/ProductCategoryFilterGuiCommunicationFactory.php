<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductCategoryFilterGui\Communication\Form\DataProvider\ProductCategoryFilterDataProvider;
use Spryker\Zed\ProductCategoryFilterGui\Communication\Form\ProductCategoryFilterForm;
use Spryker\Zed\ProductCategoryFilterGui\Communication\Table\CategoryRootNodeTable;
use Spryker\Zed\ProductCategoryFilterGui\Communication\TransferGenerator\ProductCategoryFilterTransferGenerator;
use Spryker\Zed\ProductCategoryFilterGui\ProductCategoryFilterGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductCategoryFilterGui\ProductCategoryFilterGuiConfig getConfig()
 */
class ProductCategoryFilterGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterFacadeInterface
     */
    public function getProductCategoryFilterFacade()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::FACADE_PRODUCT_CATEGORY_FILTER);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @param int $idLocale
     *
     * @return \Spryker\Zed\ProductCategoryFilterGui\Communication\Table\CategoryRootNodeTable
     */
    public function createCategoryRootNodeTable($idLocale)
    {
        return new CategoryRootNodeTable($this->getCategoryQueryContainer(), $idLocale);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToCategoryFacadeInterface
     */
    public function getCategoryFacade()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\QueryContainer\ProductCategoryFilterGuiToCategoryQueryContainerInterface
     */
    public function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\QueryContainer\ProductCategoryFilterGuiToProductCategoryQueryContainerInterface
     */
    public function getProductCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductSearchFacadeInterface
     */
    public function getProductSearchFacade()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::FACADE_PRODUCT_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductFacadeInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @param array|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductCategoryFilterForm(array $data = null, array $options = [])
    {
        return $this->getFormFactory()->create(ProductCategoryFilterForm::class, $data, $options);
    }

    /**
     * @deprecated Use `getProductCategoryFilterForm()` instead.
     *
     * @param array|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductCategoryFilterForm(array $data = null, array $options = [])
    {
        return $this->getProductCategoryFilterForm($data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Communication\Form\DataProvider\ProductCategoryFilterDataProvider
     */
    public function createProductCategoryFilterDataProvider()
    {
        return new ProductCategoryFilterDataProvider($this->getProductCategoryFilterFacade());
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Communication\TransferGenerator\ProductCategoryFilterTransferGeneratorInterface
     */
    public function createProductCategoryFilterFormatter()
    {
        return new ProductCategoryFilterTransferGenerator($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\Client\ProductCategoryFilterGuiToCatalogClientInterface
     */
    public function getCatalogClient()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::CLIENT_CATALOG);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\Client\ProductCategoryFilterGuiToProductCategoryFilterClientInterface
     */
    public function getProductCategoryFilterClient()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::CLIENT_PRODUCT_CATEGORY_FILTER);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\Service\ProductCategoryFilterGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
