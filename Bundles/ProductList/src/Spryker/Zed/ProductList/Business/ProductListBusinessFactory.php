<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductList\Business\KeyGenerator\ProductListKeyGenerator;
use Spryker\Zed\ProductList\Business\KeyGenerator\ProductListKeyGeneratorInterface;
use Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface;
use Spryker\Zed\ProductList\Business\ProductList\ProductListReader;
use Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface;
use Spryker\Zed\ProductList\Business\ProductList\ProductListWriter;
use Spryker\Zed\ProductList\Business\ProductList\ProductListWriterInterface;
use Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationPostSaver;
use Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationReader;
use Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationReaderInterface;
use Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationWriter;
use Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationWriterInterface;
use Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationPostSaver;
use Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationReader;
use Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationReaderInterface;
use Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationWriter;
use Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationWriterInterface;
use Spryker\Zed\ProductList\Business\ProductListRestrictionFilter\ProductListRestrictionFilter;
use Spryker\Zed\ProductList\Business\ProductListRestrictionFilter\ProductListRestrictionFilterInterface;
use Spryker\Zed\ProductList\Business\ProductListRestrictionValidator\ProductListRestrictionValidator;
use Spryker\Zed\ProductList\Business\ProductListRestrictionValidator\ProductListRestrictionValidatorInterface;
use Spryker\Zed\ProductList\Business\RestrictedItemsFilter\RestrictedItemsFilter;
use Spryker\Zed\ProductList\Business\RestrictedItemsFilter\RestrictedItemsFilterInterface;
use Spryker\Zed\ProductList\Dependency\Facade\ProductListToMessengerFacadeInterface;
use Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductFacadeInterface;
use Spryker\Zed\ProductList\Dependency\Service\ProductListToUtilTextServiceInterface;
use Spryker\Zed\ProductList\ProductListDependencyProvider;

/**
 * @method \Spryker\Zed\ProductList\ProductListConfig getConfig()
 * @method \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface getEntityManager()
 */
class ProductListBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductList\Business\RestrictedItemsFilter\RestrictedItemsFilterInterface
     */
    public function createRestrictedItemsFilter(): RestrictedItemsFilterInterface
    {
        return new RestrictedItemsFilter(
            $this->getMessengerFacade(),
            $this->createProductListRestrictionFilter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListRestrictionValidator\ProductListRestrictionValidatorInterface
     */
    public function createProductListRestrictionValidator(): ProductListRestrictionValidatorInterface
    {
        return new ProductListRestrictionValidator(
            $this->createProductListRestrictionFilter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListRestrictionFilter\ProductListRestrictionFilterInterface
     */
    public function createProductListRestrictionFilter(): ProductListRestrictionFilterInterface
    {
        return new ProductListRestrictionFilter(
            $this->createProductListReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface
     */
    public function createProductListReader(): ProductListReaderInterface
    {
        return new ProductListReader(
            $this->getRepository(),
            $this->createProductListCategoryRelationReader(),
            $this->createProductListProductConcreteRelationReader(),
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductList\ProductListWriterInterface
     */
    public function createProductListWriter(): ProductListWriterInterface
    {
        return new ProductListWriter(
            $this->getEntityManager(),
            $this->createProductListKeyGenerator(),
            $this->getProductListPostSaverCollection(),
            $this->getProductListPreCreatePlugins(),
            $this->getProductListPreUpdatePlugins(),
            $this->getProductListDeletePreCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface
     */
    public function createProductListCategoryRelationPostSaver(): ProductListPostSaverInterface
    {
        return new ProductListCategoryRelationPostSaver($this->createProductListCategoryRelationWriter());
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface
     */
    public function createProductListProductConcreteRelationPostSaver(): ProductListPostSaverInterface
    {
        return new ProductListProductConcreteRelationPostSaver($this->createProductListProductConcreteRelationWriter());
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationReaderInterface
     */
    public function createProductListCategoryRelationReader(): ProductListCategoryRelationReaderInterface
    {
        return new ProductListCategoryRelationReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationReaderInterface
     */
    public function createProductListProductConcreteRelationReader(): ProductListProductConcreteRelationReaderInterface
    {
        return new ProductListProductConcreteRelationReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationWriterInterface
     */
    public function createProductListCategoryRelationWriter(): ProductListCategoryRelationWriterInterface
    {
        return new ProductListCategoryRelationWriter(
            $this->getEntityManager(),
            $this->createProductListCategoryRelationReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationWriterInterface
     */
    public function createProductListProductConcreteRelationWriter(): ProductListProductConcreteRelationWriterInterface
    {
        return new ProductListProductConcreteRelationWriter(
            $this->getEntityManager(),
            $this->createProductListProductConcreteRelationReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface[]
     */
    public function getProductListPostSaverCollection(): array
    {
        return [
            $this->createProductListCategoryRelationPostSaver(),
            $this->createProductListProductConcreteRelationPostSaver(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductList\Dependency\Service\ProductListToUtilTextServiceInterface
     */
    public function getUtilTextService(): ProductListToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(ProductListDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListPreCreatePluginInterface[]
     */
    public function getProductListPreCreatePlugins(): array
    {
        return $this->getProvidedDependency(ProductListDependencyProvider::PLUGINS_PRODUCT_LIST_PRE_CREATE);
    }

    /**
     * @return \Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListPreUpdatePluginInterface[]
     */
    public function getProductListPreUpdatePlugins(): array
    {
        return $this->getProvidedDependency(ProductListDependencyProvider::PLUGINS_PRODUCT_LIST_PRE_UPDATE);
    }

    /**
     * @return \Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListDeletePreCheckPluginInterface[]
     */
    public function getProductListDeletePreCheckPlugins(): array
    {
        return $this->getProvidedDependency(ProductListDependencyProvider::PLUGINS_PRODUCT_LIST_DELETE_PRE_CHECK);
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\KeyGenerator\ProductListKeyGeneratorInterface
     */
    public function createProductListKeyGenerator(): ProductListKeyGeneratorInterface
    {
        return new ProductListKeyGenerator(
            $this->getRepository(),
            $this->getUtilTextService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Dependency\Facade\ProductListToMessengerFacadeInterface
     */
    public function getMessengerFacade(): ProductListToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(ProductListDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductFacadeInterface
     */
    protected function getProductFacade(): ProductListToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductListDependencyProvider::FACADE_PRODUCT);
    }
}
