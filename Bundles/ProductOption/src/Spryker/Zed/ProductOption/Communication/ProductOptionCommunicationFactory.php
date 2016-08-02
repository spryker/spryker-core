<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOption\Communication\Form\DataProvider\ProductOptionGroupDataProvider;
use Spryker\Zed\ProductOption\Communication\Form\ProductOptionGroupForm;
use Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueForm;
use Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueTranslationForm;
use Spryker\Zed\ProductOption\Communication\Table\ProductOptionTable;
use Spryker\Zed\ProductOption\Communication\Table\ProductTable;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 */
class ProductOptionCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param \Spryker\Zed\ProductOption\Communication\Form\DataProvider\ProductOptionGroupDataProvider $productOptionGroupDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductOptionGroup(
        ProductOptionGroupDataProvider $productOptionGroupDataProvider = null
    ){
        $productOptionValueForm = $this->createProductOptionValueForm();
        $productOptionValueTranslationForm = $this->createProductOptionValueTranslationForm();

        $productOptionGroupFormType = new ProductOptionGroupForm(
            $productOptionValueForm,
            $productOptionValueTranslationForm
        );

        return $this->getFormFactory()->create(
            $productOptionGroupFormType,
            $productOptionGroupDataProvider->getData(),
            array_merge([
                'data_class'  => ProductOptionGroupTransfer::class
            ],
                $productOptionGroupDataProvider->getOptions()
            )
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueForm
     */
    public function createProductOptionValueForm()
    {
        return new ProductOptionValueForm($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueTranslationForm
     */
    public function createProductOptionValueTranslationForm()
    {
        return new ProductOptionValueTranslationForm();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return \Spryker\Zed\ProductOption\Communication\Form\DataProvider\ProductOptionGroupDataProvider
     */
    public function createGeneralFormDataProvider(ProductOptionGroupTransfer $productOptionGroupTransfer = null)
    {
        return new ProductOptionGroupDataProvider(
            $this->getTaxFacade(),
            $productOptionGroupTransfer
        );
    }

    /**
     * @param int $idProductOptionGroup
     *
     * @return \Spryker\Zed\ProductOption\Communication\Table\ProductOptionTable
     */
    public function createProductOptionTable($idProductOptionGroup)
    {
        return new ProductOptionTable($this->getQueryContainer(), $this->getCurrentLocale(), $idProductOptionGroup);
    }

    /**
     * @param int $idProductOptionGroup
     *
     * @return \Spryker\Zed\ProductOption\Communication\Table\ProductTable
     */
    public function createProductTable($idProductOptionGroup = null)
    {
        return new ProductTable(
            $this->getQueryContainer(),
            $this->getCurrentLocale(),
            $idProductOptionGroup
        );
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale()
    {
        return $this->getLocaleFacade()->getCurrentLocale();
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_LOCALE);
    }
}
