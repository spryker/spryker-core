<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider;
use Spryker\Zed\Tax\Communication\Form\DataProvider\TaxSetFormDataProvider;
use Spryker\Zed\Tax\Communication\Form\TaxRateForm;
use Spryker\Zed\Tax\Communication\Form\TaxSetForm;
use Spryker\Zed\Tax\Communication\Form\Transform\PercentageTransformer;
use Spryker\Zed\Tax\Communication\Table\RateTable;
use Spryker\Zed\Tax\Communication\Table\SetTable;
use Spryker\Zed\Tax\TaxDependencyProvider;

/**
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 * @method \Spryker\Zed\Tax\TaxConfig getConfig()
 */
class TaxCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider|null $taxRateFormDataProvider Deprecated: TaxRateFormDataProvider must not be passed in.
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getTaxRateForm(TaxRateFormDataProvider $taxRateFormDataProvider = null)
    {
        return $this->getFormFactory()->create(
            TaxRateForm::class,
            $this->getTaxRateFormData($taxRateFormDataProvider),
            [
                 'data_class' => TaxRateTransfer::class,
              ]
        );
    }

    /**
     * @param \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider|null $taxRateFormDataProvider
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    protected function getTaxRateFormData(TaxRateFormDataProvider $taxRateFormDataProvider = null)
    {
        return $taxRateFormDataProvider ? $taxRateFormDataProvider->getData()
            : $this->createTaxRateFormDataProvider()->getData();
    }

    /**
     * @deprecated Use `getTaxRateForm()` instead.
     *
     * @param \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider|null $taxRateFormDataProvider Deprecated: TaxRateFormDataProvider must not be passed in.
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTaxRateForm(TaxRateFormDataProvider $taxRateFormDataProvider = null)
    {
        return $this->getTaxRateForm($taxRateFormDataProvider);
    }

    /**
     * @param \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxSetFormDataProvider|null $taxSetFormDataProvider Deprecated: TaxSetFormDataProvider must not be passed in.
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getTaxSetForm(TaxSetFormDataProvider $taxSetFormDataProvider = null)
    {
        return $this->getFormFactory()->create(
            TaxSetForm::class,
            $this->getTaxSetFormData($taxSetFormDataProvider),
            [
                'data_class' => TaxSetTransfer::class,
            ]
        );
    }

    /**
     * @param \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxSetFormDataProvider|null $taxSetFormDataProvider
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|null
     */
    protected function getTaxSetFormData(TaxSetFormDataProvider $taxSetFormDataProvider = null)
    {
        return $taxSetFormDataProvider ? $taxSetFormDataProvider->getData()
            : $this->createTaxSetFormDataProvider()->getData();
    }

    /**
     * @deprecated Use `getTaxSetForm()` instead.
     *
     * @param \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxSetFormDataProvider|null $taxSetFormDataProvider Deprecated: TaxSetFormDataProvider must not be passed in.
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTaxSetForm(TaxSetFormDataProvider $taxSetFormDataProvider = null)
    {
        return $this->getTaxSetForm($taxSetFormDataProvider);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxSetTransfer|null $taxSetTransfer
     *
     * @return \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxSetFormDataProvider
     */
    public function createTaxSetFormDataProvider(TaxSetTransfer $taxSetTransfer = null)
    {
        return new TaxSetFormDataProvider($this->getFacade(), $taxSetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxRateTransfer|null $taxRateTransfer
     *
     * @return \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider
     */
    public function createTaxRateFormDataProvider(TaxRateTransfer $taxRateTransfer = null)
    {
        return new TaxRateFormDataProvider($this->getCountryFacade(), $taxRateTransfer);
    }

    /**
     * @return \Spryker\Zed\Tax\Communication\Form\Transform\PercentageTransformer
     */
    public function createPercentageTransformer()
    {
        return new PercentageTransformer();
    }

    /**
     * @return \Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface
     */
    protected function getCountryFacade()
    {
        return $this->getProvidedDependency(TaxDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Zed\Tax\Communication\Table\RateTable
     */
    public function createTaxRateTable()
    {
        $taxRateQuery = $this->getQueryContainer()->queryAllTaxRates();

        return new RateTable($taxRateQuery, $this->getDateTimeService());
    }

    /**
     * @return \Spryker\Zed\Tax\Communication\Table\SetTable
     */
    public function createTaxSetTable()
    {
        $taxSetQuery = $this->getQueryContainer()->queryAllTaxSets();

        return new SetTable($taxSetQuery, $this->getDateTimeService());
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected function getDateTimeService()
    {
        return $this->getProvidedDependency(TaxDependencyProvider::SERVICE_DATE_FORMATTER);
    }
}
