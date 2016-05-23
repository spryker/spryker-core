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
use Spryker\Zed\Discount\Communication\Form\DataProvider\VoucherFormDataProvider;
use Spryker\Zed\Discount\Communication\Form\DiscountForm;
use Spryker\Zed\Discount\Communication\Form\GeneralForm;
use Spryker\Zed\Discount\Communication\Form\VoucherForm;
use Spryker\Zed\Discount\Communication\Table\DiscountsTable;
use Spryker\Zed\Discount\Communication\Table\DiscountVoucherCodesTable;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 */
class DiscountCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     *
     * @return FormInterface
     */
    public function createDiscountForm()
    {
        $discountFormType = new DiscountForm(
            $this->createGeneralFormType(),
            $this->createCalculatorFormType(),
            $this->createConditionsFormType()
        );

        return $this->getFormFactory()->create(
            $discountFormType,
            null,
            [
              'data_class'  => DiscountConfiguratorTransfer::class
            ]
        );
    }

    /**
     * @return GeneralForm
     */
    public function createGeneralFormType()
    {
        return new GeneralForm($this->getQueryContainer());
    }

    /**
     * @return CalculatorForm
     */
    public function createCalculatorFormType()
    {
        $calculatorDataProvider = $this->createCalculatorFormDataProvider();

        return new CalculatorForm($calculatorDataProvider, $this->getFacade());
    }

    /**
     * @return ConditionsForm
     */
    public function createConditionsFormType()
    {
        return new ConditionsForm($this->getFacade());
    }

    /**
     * @return VoucherForm
     */
    public function createVoucherFormType()
    {
       return new VoucherForm();
    }

    /**
     *
     * @param DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @return FormInterface
     */
    public function createVoucherForm(DiscountVoucherTransfer $discountVoucherTransfer)
    {
        $discountVoucherFormType = $this->createVoucherFormType();

        return $this->getFormFactory()->create(
            $discountVoucherFormType,
            $discountVoucherTransfer,
            [
                'data_class'  => DiscountVoucherTransfer::class
            ]
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\Table\DiscountsTable
     */
    public function createDiscountsTable()
    {
        $discountQuery = $this->getQueryContainer()->queryDiscount();

        return new DiscountsTable($discountQuery);
    }

    /**
     * @return CalculatorFormDataProvider
     */
    public function createCalculatorFormDataProvider()
    {
       return new CalculatorFormDataProvider($this->getCalculatorPlugins());
    }

    /**
     * @return VoucherFormDataProvider
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
     * @return DiscountVoucherCodesTable
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
}
