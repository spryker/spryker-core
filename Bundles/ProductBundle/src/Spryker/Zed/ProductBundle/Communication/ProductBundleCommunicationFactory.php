<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication;

use Generated\Shared\Transfer\ProductBundleGroupTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductBundle\Communication\Form\DataProvider\ProductBundleGroupDataProvider;
use Spryker\Zed\ProductBundle\Communication\Form\ProductBundleGroupForm;
use Spryker\Zed\ProductBundle\Communication\Form\ProductBundleTranslationForm;
use Spryker\Zed\ProductBundle\Communication\Form\ProductBundleValueForm;
use Spryker\Zed\ProductBundle\Communication\Form\Transformer\ArrayToArrayObjectTransformer;
use Spryker\Zed\ProductBundle\Communication\Form\Transformer\PriceTransformer;
use Spryker\Zed\ProductBundle\Communication\Form\Transformer\StringToArrayTransformer;
use Spryker\Zed\ProductBundle\Communication\Table\ProductBundleListTable;
use Spryker\Zed\ProductBundle\Communication\Table\ProductBundleTable;
use Spryker\Zed\ProductBundle\Communication\Table\ProductTable;
use Spryker\Zed\ProductBundle\Communication\Tabs\OptionTabs;
use Spryker\Zed\ProductBundle\ProductBundleDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainer getQueryContainer()
 */
class ProductBundleCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param \Spryker\Zed\ProductBundle\Communication\Form\DataProvider\ProductBundleGroupDataProvider|null $ProductBundleGroupDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductBundleGroup(ProductBundleGroupDataProvider $ProductBundleGroupDataProvider)
    {
        $ProductBundleValueForm = $this->createProductBundleValueForm();
        $createProductBundleTranslationForm = $this->createProductBundleTranslationForm();

        $ProductBundleGroupFormType = new ProductBundleGroupForm(
            $ProductBundleValueForm,
            $createProductBundleTranslationForm,
            $this->createArrayToArrayObjectTransformer(),
            $this->createStringToArrayTransformer(),
            $this->getQueryContainer()
        );

        return $this->getFormFactory()->create(
            $ProductBundleGroupFormType,
            $ProductBundleGroupDataProvider->getData(),
            array_merge(
                [
                'data_class' => ProductBundleGroupTransfer::class
                ],
                $ProductBundleGroupDataProvider->getOptions()
            )
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Communication\Form\ProductBundleValueForm
     */
    public function createProductBundleValueForm()
    {
        return new ProductBundleValueForm($this->getQueryContainer(), $this->createPriceTranformer());
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Communication\Form\ProductBundleTranslationForm
     */
    public function createProductBundleTranslationForm()
    {
        return new ProductBundleTranslationForm();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleGroupTransfer|null $ProductBundleGroupTransfer
     *
     * @return \Spryker\Zed\ProductBundle\Communication\Form\DataProvider\ProductBundleGroupDataProvider
     */
    public function createGeneralFormDataProvider(ProductBundleGroupTransfer $ProductBundleGroupTransfer = null)
    {
        return new ProductBundleGroupDataProvider(
            $this->getTaxFacade(),
            $this->getLocaleFacade(),
            $ProductBundleGroupTransfer
        );
    }

    /**
     * @param int $idProductBundleGroup
     * @param string $tableContext
     *
     * @return \Spryker\Zed\ProductBundle\Communication\Table\ProductBundleTable
     */
    public function createProductBundleTable($idProductBundleGroup, $tableContext)
    {
        return new ProductBundleTable(
            $this->getQueryContainer(),
            $this->getCurrentLocale(),
            $idProductBundleGroup,
            $tableContext
        );
    }

    /**
     * @param int|null $idProductBundleGroup
     *
     * @return \Spryker\Zed\ProductBundle\Communication\Table\ProductTable
     */
    public function createProductTable($idProductBundleGroup = null)
    {
        return new ProductTable(
            $this->getQueryContainer(),
            $this->getCurrentLocale(),
            $idProductBundleGroup
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Communication\Table\ProductBundleListTable
     */
    public function createProductBundleListTable()
    {
        return new ProductBundleListTable(
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
     * @param \Symfony\Component\Form\FormInterface $ProductBundleGroupForm
     *
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createOptionTabs(FormInterface $ProductBundleGroupForm)
    {
        return new OptionTabs($ProductBundleGroupForm);
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    protected function createArrayToArrayObjectTransformer()
    {
        return new ArrayToArrayObjectTransformer();
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    protected function createStringToArrayTransformer()
    {
        return new StringToArrayTransformer();
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    protected function createPriceTranformer()
    {
        return new PriceTransformer($this->getMoneyFacade());
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToTaxInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToMoneyInterface
     */
    public function getMoneyFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToGlossaryInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::FACADE_GLOSSARY);
    }

}
