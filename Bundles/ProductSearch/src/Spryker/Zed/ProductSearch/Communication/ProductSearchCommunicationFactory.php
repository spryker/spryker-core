<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication;

use Spryker\Shared\ProductSearch\Code\KeyBuilder\FilterGlossaryKeyBuilder;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSearch\Communication\Form\CleanSearchPreferencesForm;
use Spryker\Zed\ProductSearch\Communication\Form\DataProvider\FilterPreferencesDataProvider;
use Spryker\Zed\ProductSearch\Communication\Form\DataProvider\SearchPreferencesDataProvider;
use Spryker\Zed\ProductSearch\Communication\Form\DeleteFilterPreferencesForm;
use Spryker\Zed\ProductSearch\Communication\Form\FilterPreferencesForm;
use Spryker\Zed\ProductSearch\Communication\Form\SearchPreferencesForm;
use Spryker\Zed\ProductSearch\Communication\Table\FilterPreferencesTable;
use Spryker\Zed\ProductSearch\Communication\Table\SearchPreferencesTable;
use Spryker\Zed\ProductSearch\Communication\Transfer\AttributeFormTransferMapper;
use Spryker\Zed\ProductSearch\Communication\Transfer\SortedProductSearchTransferListMapper;
use Spryker\Zed\ProductSearch\ProductSearchDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSearch\ProductSearchConfig getConfig()
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface getFacade()
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
        return $this->getFormFactory()->create(SearchPreferencesForm::class, $data, $options);
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
        return $this->getFormFactory()->create(FilterPreferencesForm::class, $data, $options);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCleanSearchPreferencesForm(): FormInterface
    {
        return $this->getFormFactory()->create(CleanSearchPreferencesForm::class, [], [
            'fields' => [],
        ]);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteFilterPreferencesForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeleteFilterPreferencesForm::class, [], [
            'fields' => [],
        ]);
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
