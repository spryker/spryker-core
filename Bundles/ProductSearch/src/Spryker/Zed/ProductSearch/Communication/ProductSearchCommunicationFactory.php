<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication;

use Spryker\Shared\ProductSearch\Code\KeyBuilder\FilterGlossaryKeyBuilder;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSearch\Communication\Form\DataProvider\FilterPreferencesDataProvider;
use Spryker\Zed\ProductSearch\Communication\Form\DataProvider\SearchPreferencesDataProvider;
use Spryker\Zed\ProductSearch\Communication\Form\FilterPreferencesForm;
use Spryker\Zed\ProductSearch\Communication\Form\SearchPreferencesForm;
use Spryker\Zed\ProductSearch\Communication\Table\FilterPreferencesTable;
use Spryker\Zed\ProductSearch\Communication\Table\SearchPreferencesTable;
use Spryker\Zed\ProductSearch\Communication\Transfer\AttributeFormTransferMapper;
use Spryker\Zed\ProductSearch\Communication\Transfer\SortedProductSearchTransferListMapper;
use Spryker\Zed\ProductSearch\ProductSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductSearch\ProductSearchConfig getConfig()
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacade getFacade()
 */
class ProductSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductSearch\Communication\Table\SearchPreferencesTable
     */
    public function createSearchPreferencesTable()
    {
        return new SearchPreferencesTable($this->getQueryContainer());
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSearchPreferencesForm(array $data = [], array $options = [])
    {
        $filterFormType = new SearchPreferencesForm($this->getQueryContainer());

        return $this->getFormFactory()->create($filterFormType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Communication\Form\DataProvider\SearchPreferencesDataProvider
     */
    public function createSearchPreferencesDataProvider()
    {
        return new SearchPreferencesDataProvider($this->getQueryContainer());
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFilterPreferencesForm(array $data = [], array $options = [])
    {
        $filterFormType = new FilterPreferencesForm($this->getQueryContainer());

        return $this->getFormFactory()->create($filterFormType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Communication\Form\DataProvider\FilterPreferencesDataProvider
     */
    public function createFilterPreferencesDataProvider()
    {
        return new FilterPreferencesDataProvider(
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->getLocaleFacade(),
            $this->getGlossaryFacade(),
            $this->createFilterGlossaryKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Communication\Table\FilterPreferencesTable
     */
    public function createFilterPreferencesTable()
    {
        return new FilterPreferencesTable($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToGlossaryInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Communication\Transfer\AttributeFormTransferMapperInterface
     */
    public function createAttributeFormTransferMapper()
    {
        return new AttributeFormTransferMapper();
    }

    /**
     * @return \Spryker\Shared\ProductSearch\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected function createFilterGlossaryKeyBuilder()
    {
        return new FilterGlossaryKeyBuilder();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Communication\Transfer\SortedProductSearchTransferListMapperInterface
     */
    public function createSortedProductSearchTransferListMapper()
    {
        return new SortedProductSearchTransferListMapper();
    }
}
