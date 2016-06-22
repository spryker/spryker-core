<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider;
use Spryker\Zed\Tax\Communication\Form\TaxRateForm;
use Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface;
use Spryker\Zed\Tax\TaxDependencyProvider;

class TaxCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTaxRateForm()
    {
        $taxRateForm = new TaxRateForm($this->createTaxRateFormDataProvider());

        return $this->getFormFactory()->create($taxRateForm);
    }

    /**
     * @return \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider
     */
    protected function createTaxRateFormDataProvider()
    {
        return new TaxRateFormDataProvider($this->getCountryFacade());
    }

    /**
     * @return TaxToCountryBridgeInterface
     */
    protected function getCountryFacade()
    {
        return $this->getProvidedDependency(TaxDependencyProvider::FACADE_COUNTRY);
    }
}
