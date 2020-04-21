<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint\UniqueUrl;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantProfileAddressFormDataProvider;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantProfileAddressFormDataProviderInterface;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantProfileFormDataProvider;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantProfileFormDataProviderInterface;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\MerchantProfileForm;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToCountryFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToGlossaryFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToLocaleFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToMerchantFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToUrlFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\MerchantProfileGuiPageDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGuiPage\MerchantProfileGuiPageConfig getConfig()
 */
class MerchantProfileGuiPageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createMerchantProfileForm(?MerchantTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantProfileForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantProfileFormDataProviderInterface
     */
    public function createMerchantProfileFormDataProvider(): MerchantProfileFormDataProviderInterface
    {
        return new MerchantProfileFormDataProvider(
            $this->getConfig(),
            $this->getMerchantFacade(),
            $this->getGlossaryFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantProfileAddressFormDataProviderInterface
     */
    public function createMerchantProfileAddressFormDataProvider(): MerchantProfileAddressFormDataProviderInterface
    {
        return new MerchantProfileAddressFormDataProvider($this->getCountryFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint\UniqueUrl
     */
    public function createUniqueUrlConstraint(): UniqueUrl
    {
        return new UniqueUrl();
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantProfileGuiPageToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileGuiPageDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantProfileGuiPageToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileGuiPageDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): MerchantProfileGuiPageToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileGuiPageDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToLocaleFacadeInterface
     */
    public function getLocaleFacade(): MerchantProfileGuiPageToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileGuiPageDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToUrlFacadeInterface
     */
    public function getUrlFacade(): MerchantProfileGuiPageToUrlFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileGuiPageDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToCountryFacadeInterface
     */
    public function getCountryFacade(): MerchantProfileGuiPageToCountryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileGuiPageDependencyProvider::FACADE_COUNTRY);
    }
}
