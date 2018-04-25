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
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface getQueryContainer()
 */
class ProductOptionCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @deprecated Use `getProductOptionGroupType()` instead.
     *
     * @param \Spryker\Zed\ProductOption\Communication\Form\DataProvider\ProductOptionGroupDataProvider $productOptionGroupDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductOptionGroup(ProductOptionGroupDataProvider $productOptionGroupDataProvider)
    {
        return $this->getFormFactory()->create(
            ProductOptionGroupForm::class,
            $productOptionGroupDataProvider->getData(),
            array_merge(
                [
                'data_class' => ProductOptionGroupTransfer::class,
                ],
                $productOptionGroupDataProvider->getOptions()
            )
        );
    }

    /**
     * @param \Spryker\Zed\ProductOption\Communication\Form\DataProvider\ProductOptionGroupDataProvider $productOptionGroupDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductOptionGroupForm(ProductOptionGroupDataProvider $productOptionGroupDataProvider)
    {
        return $this->createProductOptionGroup($productOptionGroupDataProvider);
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    public function createProductOptionValueForm()
    {
        return ProductOptionValueForm::class;
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    public function createProductOptionTranslationForm()
    {
        return ProductOptionTranslationForm::class;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer|null $productOptionGroupTransfer
     *
     * @return \Spryker\Zed\ProductOption\Communication\Form\DataProvider\ProductOptionGroupDataProvider
     */
    public function createGeneralFormDataProvider(?ProductOptionGroupTransfer $productOptionGroupTransfer = null)
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
            $this->getUtilEncodingService(),
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
            $this->getUtilEncodingService(),
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
            $this->getCurrencyFacade(),
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
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createArrayToArrayObjectTransformer()
    {
        return new ArrayToArrayObjectTransformer();
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createStringToArrayTransformer()
    {
        return new StringToArrayTransformer();
    }

    /**
     * @deprecated Please use `createPriceTransformer()` instead.
     *
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createPriceTranformer()
    {
        return $this->createPriceTransformer();
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createPriceTransformer()
    {
        return new PriceTransformer($this->getMoneyFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleFacadeInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyFacadeInterface
     */
    public function getMoneyFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface
     */
    public function getCurrencyFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Service\ProductOptionToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getMoneyCollectionFormTypePlugin()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::MONEY_COLLECTION_FORM_TYPE_PLUGIN);
    }
}
