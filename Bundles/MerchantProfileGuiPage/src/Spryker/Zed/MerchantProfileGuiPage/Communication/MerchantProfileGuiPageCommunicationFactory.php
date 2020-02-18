<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint\UniqueUrl;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantProfileAddressFormDataProvider;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantProfileFormDataProvider;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantUpdateFormDataProvider;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\MerchantForm;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToCountryFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToGlossaryFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToLocaleFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToUrlFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\MerchantProfileGuiPageDependencyProvider;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToMerchantFacadeInterface;
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
    public function getMerchantForm(?MerchantTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantUpdateFormDataProvider
     */
    public function createMerchantUpdateFormDataProvider(): MerchantUpdateFormDataProvider
    {
        return new MerchantUpdateFormDataProvider($this->getMerchantFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantProfileFormDataProvider
     */
    public function createMerchantProfileFormDataProvider(): MerchantProfileFormDataProvider
    {
        return new MerchantProfileFormDataProvider(
            $this->getConfig(),
            $this->getGlossaryFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantProfileAddressFormDataProvider
     */
    public function createMerchantProfileAddressFormDataProvider(): MerchantProfileAddressFormDataProvider
    {
        return new MerchantProfileAddressFormDataProvider(
            $this->getCountryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint\UniqueUrl
     */
    public function createUniqueUrlConstraint(): UniqueUrl
    {
        return new UniqueUrl([
            UniqueUrl::OPTION_URL_FACADE => $this->getUrlFacade(),
        ]);
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
