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
use Spryker\Zed\Discount\Communication\Form\DataProvider\VoucherFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\DiscountForm;
use Spryker\Zed\Discount\Communication\Form\Transformer\CalculatorAmountTransformer;
use Spryker\Zed\Discount\Communication\Form\VoucherForm;
use Spryker\Zed\Discount\Communication\QueryBuilderTransformer\JavascriptQueryBuilderTransformer;
use Spryker\Zed\Discount\Communication\Table\DiscountsTable;
use Spryker\Zed\Discount\Communication\Table\DiscountVoucherCodesTable;
use Spryker\Zed\Discount\Communication\Tabs\DiscountFormTabs;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Money\Communication\Form\Type\MoneyCollectionType;
use Spryker\Zed\Money\Communication\Form\Type\MoneyType;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 */
class DiscountCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param int|null $idDiscount
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getDiscountForm($idDiscount = null)
    {
        $discountDataProvider = $this->createDiscountDataProvider();

        return $this->getFormFactory()->create(
            DiscountForm::class,
            $discountDataProvider->getData($idDiscount),
            [
                'data_class' => DiscountConfiguratorTransfer::class,
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getVoucherForm(DiscountVoucherTransfer $discountVoucherTransfer)
    {
        return $this->getFormFactory()->create(
            VoucherForm::class,
            $discountVoucherTransfer,
            [
                'data_class' => DiscountVoucherTransfer::class,
            ]
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\Transformer\CalculatorAmountTransformer|\Symfony\Component\Form\DataTransformerInterface
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

        return new DiscountsTable($discountQuery, $this->getQueryContainer(), $this->getCalculatorPlugins());
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
    public function createVoucherFormDataProvider()
    {
        $voucherFormDataProvider = new VoucherFormDataProvider();
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
            $batchValue
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\QueryBuilderTransformer\JavascriptQueryBuilderTransformerInterface
     */
    public function createJavascriptQueryBuilderTransformer()
    {
        return new JavascriptQueryBuilderTransformer($this->getFacade());
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\DataProvider\DiscountFormDataProvider
     */
    protected function createDiscountDataProvider()
    {
        $discountFormDataProvider = new DiscountFormDataProvider($this->getCurrencyFacade());
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
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyInterface
     */
    public function getMoneyFacade()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\Form\DiscountFormExpanderPluginInterface[]
     */
    public function getDiscountFormTypeExpanderPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNT_FORM_TYPE_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\Form\DiscountFormDataProviderExpanderPluginInterface[]
     */
    protected function getDiscountFormDataProviderExpanderPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNT_FORM_DATA_PROVIDER_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountViewBlockProviderPluginInterface[]
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
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
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
}
