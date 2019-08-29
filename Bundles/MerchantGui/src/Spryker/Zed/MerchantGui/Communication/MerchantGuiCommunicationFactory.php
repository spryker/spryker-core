<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication;

use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantAddressFormDataProvider;
use Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantFormDataProvider;
use Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantUpdateFormDataProvider;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantForm;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantUpdateForm;
use Spryker\Zed\MerchantGui\Communication\Table\MerchantTable;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToCountryFacadeInterface;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantGui\MerchantGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MerchantGui\MerchantGuiConfig getConfig()
 */
class MerchantGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantGui\Communication\Table\MerchantTable
     */
    public function createMerchantTable(): MerchantTable
    {
        return new MerchantTable($this->getPropelMerchantQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantForm(?MerchantTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantForm::class, $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer|null $merchantTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantUpdateForm(?MerchantTransfer $merchantTransfer = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantUpdateForm::class, $merchantTransfer, $options);
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantFormDataProvider
     */
    public function createMerchantFormDataProvider(): MerchantFormDataProvider
    {
        return new MerchantFormDataProvider(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantUpdateFormDataProvider
     */
    public function createMerchantUpdateFormDataProvider(): MerchantUpdateFormDataProvider
    {
        return new MerchantUpdateFormDataProvider(
            $this->getMerchantFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantAddressFormDataProvider
     */
    public function createMerchantAddressFormDataProvider(): MerchantAddressFormDataProvider
    {
        return new MerchantAddressFormDataProvider(
            $this->getCountryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToCountryFacadeInterface
     */
    public function getCountryFacade(): MerchantGuiToCountryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantGuiToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    public function getPropelMerchantQuery(): SpyMerchantQuery
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::PROPEL_MERCHANT_QUERY);
    }
}
