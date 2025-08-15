<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication;

use Generated\Shared\Transfer\DataTablesTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Spryker\Zed\Discount\Communication\AmountFormatter\DiscountAmountFormatter;
use Spryker\Zed\Discount\Communication\Form\DataProvider\CalculatorFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\DataProvider\DiscountFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\DataProvider\TableFilterFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\DataProvider\VoucherFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\DeleteVoucherCodeForm;
use Spryker\Zed\Discount\Communication\Form\DiscountForm;
use Spryker\Zed\Discount\Communication\Form\DiscountVisibilityForm;
use Spryker\Zed\Discount\Communication\Form\TableFilterForm;
use Spryker\Zed\Discount\Communication\Form\Transformer\CalculatorAmountTransformer;
use Spryker\Zed\Discount\Communication\Form\VoucherForm;
use Spryker\Zed\Discount\Communication\QueryBuilderTransformer\JavascriptQueryBuilderTransformer;
use Spryker\Zed\Discount\Communication\Table\DiscountsTable;
use Spryker\Zed\Discount\Communication\Table\DiscountVoucherCodesTable;
use Spryker\Zed\Discount\Communication\Tabs\DiscountFormTabs;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToLocaleFacadeInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToTranslatorFacadeInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\Money\Communication\Form\Type\MoneyCollectionType;
use Spryker\Zed\Money\Communication\Form\Type\MoneyType;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface getRepository()
 * @method \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface getEntityManager()
 */
class DiscountCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param int|null $idDiscount
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer|null $discountConfiguratorTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getDiscountForm($idDiscount = null, ?DiscountConfiguratorTransfer $discountConfiguratorTransfer = null)
    {
        return $this->getFormFactory()->create(
            DiscountForm::class,
            $discountConfiguratorTransfer ?: $this->createDiscountFormDataProvider()->getData($idDiscount),
            $this->createDiscountFormDataProvider()->getOptions(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     * @param array<string, string> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getVoucherForm(DiscountVoucherTransfer $discountVoucherTransfer, array $options): FormInterface
    {
        return $this->getFormFactory()->create(
            VoucherForm::class,
            $discountVoucherTransfer,
            $options,
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getDeleteVoucherCodeForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeleteVoucherCodeForm::class, null, [
            'fields' => [],
        ]);
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\Transformer\CalculatorAmountTransformer|\Symfony\Component\Form\DataTransformerInterface<int|null, string|array|null>
     */
    public function createCalculatorAmountTransformer()
    {
        return new CalculatorAmountTransformer($this->getCalculatorPlugins());
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Table\DiscountsTable|\Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createDiscountsTable()
    {
        $discountQuery = $this->getQueryContainer()->queryDiscount();

        return new DiscountsTable(
            $discountQuery,
            $this->getQueryContainer(),
            $this->getCalculatorPlugins(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\DataProvider\CalculatorFormDataProvider
     */
    public function createCalculatorFormDataProvider()
    {
        $calculatorDataProvider = new CalculatorFormDataProvider($this->getCalculatorPlugins());
        $calculatorDataProvider->applyFormDataExpanderPlugins($this->getDiscountFormDataProviderExpanderPlugins());

        return $calculatorDataProvider;
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\DataProvider\VoucherFormDataProvider
     */
    public function createVoucherFormDataProvider(): VoucherFormDataProvider
    {
        $voucherFormDataProvider = new VoucherFormDataProvider($this->getLocaleFacade());
        $voucherFormDataProvider->applyFormDataExpanderPlugins($this->getDiscountFormDataProviderExpanderPlugins());

        return $voucherFormDataProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\DataTablesTransfer $dataTablesTransfer
     * @param int $idPool
     * @param int $idDiscount
     * @param int $batchValue
     *
     * @return \Spryker\Zed\Discount\Communication\Table\DiscountVoucherCodesTable
     */
    public function createDiscountVoucherCodesTable(DataTablesTransfer $dataTablesTransfer, $idPool, $idDiscount, $batchValue)
    {
        return new DiscountVoucherCodesTable(
            $dataTablesTransfer,
            $this->getQueryContainer(),
            $idPool,
            $idDiscount,
            $batchValue,
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\QueryBuilderTransformer\JavascriptQueryBuilderTransformerInterface
     */
    public function createJavascriptQueryBuilderTransformer()
    {
        /** @var \Spryker\Zed\Discount\Business\DiscountFacade $facade */
        $facade = $this->getFacade();

        return new JavascriptQueryBuilderTransformer($facade);
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\DataProvider\DiscountFormDataProvider
     */
    public function createDiscountFormDataProvider()
    {
        $discountFormDataProvider = new DiscountFormDataProvider($this->getFacade(), $this->getLocaleFacade());
        $discountFormDataProvider->applyFormDataExpanderPlugins($this->getDiscountFormDataProviderExpanderPlugins());

        return $discountFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $discountForm
     * @param \Symfony\Component\Form\FormInterface|null $voucherForm
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer|null $discountConfiguratorTransfer
     *
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createDiscountFormTabs(
        FormInterface $discountForm,
        ?FormInterface $voucherForm = null,
        ?DiscountConfiguratorTransfer $discountConfiguratorTransfer = null
    ) {
        return new DiscountFormTabs($discountForm, $voucherForm, $discountConfiguratorTransfer);
    }

    /**
     * @return \Spryker\Zed\Money\Communication\Form\Type\MoneyType|\Symfony\Component\Form\FormInterface
     */
    public function createMoneyAmountFormType()
    {
        return new MoneyType();
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\AmountFormatter\DiscountAmountFormatterInterface
     */
    public function createDiscountAmountFormatter()
    {
        return new DiscountAmountFormatter($this->getCalculatorPlugins());
    }

    /**
     * @return \Spryker\Zed\Money\Communication\Form\Type\MoneyCollectionType
     */
    public function createPriceCollectionType()
    {
        return new MoneyCollectionType();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDiscountVisibilityForm(): FormInterface
    {
        return $this->getFormFactory()->create(DiscountVisibilityForm::class);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyInterface
     */
    public function getMoneyFacade()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return array<\Spryker\Zed\Discount\Dependency\Plugin\Form\DiscountFormExpanderPluginInterface>
     */
    public function getDiscountFormTypeExpanderPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNT_FORM_TYPE_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\Discount\Dependency\Plugin\Form\DiscountFormDataProviderExpanderPluginInterface>
     */
    protected function getDiscountFormDataProviderExpanderPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNT_FORM_DATA_PROVIDER_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountViewBlockProviderPluginInterface>
     */
    public function getDiscountViewBlockProviderPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNT_VIEW_BLOCK_PROVIDER);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToCurrencyInterface
     */
    public function getCurrencyFacade()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToLocaleFacadeInterface
     */
    public function getLocaleFacade(): DiscountToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface
     */
    public function getStoreFacade(): DiscountToStoreFacadeInterface
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_STORE);
    }

    /**
     * @return array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface>
     */
    public function getCalculatorPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::CALCULATOR_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getStoreRelationFormTypePlugin()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): DiscountToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getMoneyCollectionFormTypePlugin(): FormTypeInterface
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_MONEY_COLLECTION_FORM_TYPE);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTableFilterForm(): FormInterface
    {
        $tableFilterFormDataProvider = $this->createTableFilterFormDataProvider();

        return $this->getFormFactory()->create(
            TableFilterForm::class,
            $tableFilterFormDataProvider->getData(),
            $tableFilterFormDataProvider->getOptions(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\DataProvider\TableFilterFormDataProvider
     */
    public function createTableFilterFormDataProvider(): TableFilterFormDataProvider
    {
        return new TableFilterFormDataProvider(
            $this->getStoreFacade(),
        );
    }
}
