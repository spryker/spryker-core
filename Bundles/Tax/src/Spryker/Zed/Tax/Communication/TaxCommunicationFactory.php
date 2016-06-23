<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication;

use Generated\Shared\Transfer\TaxRateTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider;
use Spryker\Zed\Tax\Communication\Form\TaxRateForm;
use Spryker\Zed\Tax\TaxDependencyProvider;

/**
 * @method \Spryker\Zed\Tax\TaxConfig getConfig()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainer getQueryContainer()
 */
class TaxCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTaxRateForm(TaxRateTransfer $taxRateTransfer = null)
    {
        $taxRateForm = new TaxRateForm($this->createTaxRateFormDataProvider());

        return $this->getFormFactory()->create(
              $taxRateForm,
              $taxRateTransfer,
              [
                 'data_class' => TaxRateTransfer::class
              ]
        );
    }

    /**
     * @return \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider
     */
    protected function createTaxRateFormDataProvider()
    {
        return new TaxRateFormDataProvider($this->getCountryFacade());
    }

    /**
     * @return \Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface
     */
    protected function getCountryFacade()
    {
        return $this->getProvidedDependency(TaxDependencyProvider::FACADE_COUNTRY);
    }

}
