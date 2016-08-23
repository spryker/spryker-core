<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication;

use Generated\Shared\Transfer\DataTablesTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Discount\Communication\Form\CalculatorForm;
use Spryker\Zed\Discount\Communication\Form\ConditionsForm;
use Spryker\Zed\Discount\Communication\Form\DataProvider\CalculatorFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\DataProvider\DiscountFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\DataProvider\VoucherFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\DiscountForm;
use Spryker\Zed\Discount\Communication\Form\GeneralForm;
use Spryker\Zed\Discount\Communication\Form\Transformer\CalculatorAmountTransformer;
use Spryker\Zed\Discount\Communication\Form\VoucherForm;
use Spryker\Zed\Discount\Communication\QueryBuilderTransformer\JavascriptQueryBuilderTransformer;
use Spryker\Zed\Discount\Communication\Table\DiscountsTable;
use Spryker\Zed\Discount\Communication\Table\DiscountVoucherCodesTable;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 */
class DiscountCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param int $idDiscount
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDiscountForm($idDiscount = null)
    {
        $discountDataProvider = $this->createDiscountDataProvider();

        $discountFormType = new DiscountForm(
            $this->createGeneralFormType(),
            $this->createCalculatorFormType(),
            $this->createConditionsFormType()
        );

        return $this->getFormFactory()->create(
            $discountFormType,
            $discountDataProvider->getData($idDiscount),
            [
              'data_class' => DiscountConfiguratorTransfer::class
            ]
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\GeneralForm
     */
    public function createGeneralFormType()
    {
        return new GeneralForm($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\CalculatorForm
     */
    public function createCalculatorFormType()
    {
        $calculatorDataProvider = $this->createCalculatorFormDataProvider();

        return new CalculatorForm(
            $calculatorDataProvider,
            $this->getFacade(),
            $this->getCalculatorPlugins(),
            $this->createCalculatorAmountTransformer()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\ConditionsForm
     */
    public function createConditionsFormType()
    {
        return new ConditionsForm($this->getFacade());
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\VoucherForm
     */
    public function createVoucherFormType()
    {
        return new VoucherForm();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createVoucherForm(DiscountVoucherTransfer $discountVoucherTransfer)
    {
        $discountVoucherFormType = $this->createVoucherFormType();

        return $this->getFormFactory()->create(
            $discountVoucherFormType,
            $discountVoucherTransfer,
            [
                'data_class' => DiscountVoucherTransfer::class
            ]
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\Transformer\CalculatorAmountTransformer
     */
    public function createCalculatorAmountTransformer()
    {
        return new CalculatorAmountTransformer($this->getCalculatorPlugins());
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Table\DiscountsTable
     */
    public function createDiscountsTable()
    {
        $discountQuery = $this->getQueryContainer()->queryDiscount();

        return new DiscountsTable($discountQuery, $this->getCalculatorPlugins());
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\DataProvider\CalculatorFormDataProvider
     */
    public function createCalculatorFormDataProvider()
    {
        return new CalculatorFormDataProvider($this->getCalculatorPlugins());
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Form\DataProvider\VoucherFormDataProvider
     */
    public function createVoucherFormDataProvider()
    {
        return new VoucherFormDataProvider();
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
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory()
    {
        return (new Pimple())->getApplication()[self::FORM_FACTORY];
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    public function getCalculatorPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::CALCULATOR_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\QueryBuilderTransformer\JavascriptQueryBuilderTransformer
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
        return new DiscountFormDataProvider();
    }

}
