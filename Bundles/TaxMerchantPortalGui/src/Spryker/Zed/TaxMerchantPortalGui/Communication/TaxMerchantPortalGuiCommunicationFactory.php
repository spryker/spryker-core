<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxMerchantPortalGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\TaxMerchantPortalGui\Communication\Form\DataProvider\TaxProductAbstractFormDataProvider;
use Spryker\Zed\TaxMerchantPortalGui\Communication\Form\DataProvider\TaxProductAbstractFormDataProviderInterface;
use Spryker\Zed\TaxMerchantPortalGui\Dependency\Facade\TaxMerchantPortalGuiToTaxFacadeInterface;
use Spryker\Zed\TaxMerchantPortalGui\TaxMerchantPortalGuiDependencyProvider;

class TaxMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\TaxMerchantPortalGui\Communication\Form\DataProvider\TaxProductAbstractFormDataProviderInterface
     */
    public function createTaxProductAbstractFormDataProvider(): TaxProductAbstractFormDataProviderInterface
    {
        return new TaxProductAbstractFormDataProvider($this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\TaxMerchantPortalGui\Dependency\Facade\TaxMerchantPortalGuiToTaxFacadeInterface
     */
    public function getTaxFacade(): TaxMerchantPortalGuiToTaxFacadeInterface
    {
        return $this->getProvidedDependency(TaxMerchantPortalGuiDependencyProvider::FACADE_TAX);
    }
}
