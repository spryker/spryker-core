<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication;

use Generated\Shared\Transfer\TaxRateTransfer;
use Spryker\Shared\Library\DateFormatterInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider;
use Spryker\Zed\Tax\Communication\Form\TaxRateForm;
use Spryker\Zed\Tax\Communication\Table\RateTable;
use Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface;
use Spryker\Zed\Tax\TaxDependencyProvider;

/**
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 */
class TaxCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider $taxRateFormDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     *
     */
    public function createTaxRateForm(TaxRateFormDataProvider $taxRateFormDataProvider)
    {
        $taxRateForm = new TaxRateForm($taxRateFormDataProvider);

        return $this->getFormFactory()->create(
              $taxRateForm,
              $taxRateFormDataProvider->getData(),
              [
                 'data_class' => TaxRateTransfer::class
              ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider
     */
    public function createTaxRateFormDataProvider(TaxRateTransfer $taxRateTransfer = null)
    {
        return new TaxRateFormDataProvider($this->getCountryFacade(), $taxRateTransfer);
    }

    /**
     * @return TaxToCountryBridgeInterface
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

        return new RateTable($taxRateQuery, $this->getDateFormatter());
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return DateFormatterInterface
     */
    protected function getDateFormatter()
    {
        return $this->getProvidedDependency(TaxDependencyProvider::SERVICE_DATE_FORMATTER);
    }
}
