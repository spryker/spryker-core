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
use Spryker\Zed\ProductOption\Communication\Form\ProductOptionTranslationForm;
use Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueForm;
use Spryker\Zed\ProductOption\Communication\Form\Transformer\ArrayToArrayObjectTransformer;
use Spryker\Zed\ProductOption\Communication\Form\Transformer\PriceTransformer;
use Spryker\Zed\ProductOption\Communication\Form\Transformer\StringToArrayTransformer;
use Spryker\Zed\ProductOption\Communication\Table\ProductOptionListTable;
use Spryker\Zed\ProductOption\Communication\Table\ProductOptionTable;
use Spryker\Zed\ProductOption\Communication\Table\ProductTable;
use Spryker\Zed\ProductOption\Communication\Tabs\OptionTabs;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 */
class ProductOptionCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param \Spryker\Zed\ProductOption\Communication\Form\DataProvider\ProductOptionGroupDataProvider|null $productOptionGroupDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductOptionGroup(ProductOptionGroupDataProvider $productOptionGroupDataProvider)
    {
        $productOptionValueForm = $this->createProductOptionValueForm();
        $createProductOptionTranslationForm = $this->createProductOptionTranslationForm();

        $productOptionGroupFormType = new ProductOptionGroupForm(
            $productOptionValueForm,
            $createProductOptionTranslationForm,
            $this->createArrayToArrayObjectTransformer(),
            $this->createStringToArrayTransformer()
        );

        return $this->getFormFactory()->create(
            $productOptionGroupFormType,
            $productOptionGroupDataProvider->getData(),
            array_merge(
                [
                'data_class' => ProductOptionGroupTransfer::class
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
        return new ProductOptionValueForm($this->getQueryContainer(), $this->createPriceTranformer());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Communication\Form\ProductOptionTranslationForm
     */
    public function createProductOptionTranslationForm()
    {
        return new ProductOptionTranslationForm();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer|null $productOptionGroupTransfer
     *
     * @return \Spryker\Zed\ProductOption\Communication\Form\DataProvider\ProductOptionGroupDataProvider
     */
    public function createGeneralFormDataProvider(ProductOptionGroupTransfer $productOptionGroupTransfer = null)
    {
        return new ProductOptionGroupDataProvider(
            $this->getTaxFacade(),
            $this->getLocaleFacade(),
            $productOptionGroupTransfer
        );
    }

    /**
     * @param int $idProductOptionGroup
     * @param string $tableContext
     *
     * @return \Spryker\Zed\ProductOption\Communication\Table\ProductOptionTable
     */
    public function createProductOptionTable($idProductOptionGroup, $tableContext)
    {
        return new ProductOptionTable(
            $this->getQueryContainer(),
            $this->getCurrentLocale(),
            $idProductOptionGroup,
            $tableContext
        );
    }

    /**
     * @param int|null $idProductOptionGroup
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
     * @return \Spryker\Zed\ProductOption\Communication\Table\ProductOptionListTable
     */
    public function createProductOptionListTable()
    {
        return new ProductOptionListTable(
            $this->getQueryContainer(),
            $this->getMoneyFacade()
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
     * @param \Symfony\Component\Form\FormInterface $productOptionGroupForm
     *
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createOptionTabs(FormInterface $productOptionGroupForm)
    {
        return new OptionTabs($productOptionGroupForm);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Communication\Form\Transformer\ArrayToArrayObjectTransformer
     */
    protected function createArrayToArrayObjectTransformer()
    {
        return new ArrayToArrayObjectTransformer();
    }

    /**
     * @return \Spryker\Zed\ProductOption\Communication\Form\Transformer\StringToArrayTransformer
     */
    protected function createStringToArrayTransformer()
    {
        return new StringToArrayTransformer();
    }

    /**
     * @return \Spryker\Zed\ProductOption\Communication\Form\Transformer\PriceTransformer
     */
    protected function createPriceTranformer()
    {
        return new PriceTransformer($this->getMoneyFacade());
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

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyInterface
     */
    public function getMoneyFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_GLOSSARY);
    }

}
