<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Constraint\UniqueUrl;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\DataProvider\MerchantProfileAddressFormDataProvider;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\DataProvider\MerchantProfileAddressFormDataProviderInterface;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\DataProvider\MerchantProfileFormDataProvider;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\DataProvider\MerchantProfileFormDataProviderInterface;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\MerchantProfileForm;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToCountryFacadeInterface;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToGlossaryFacadeInterface;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToUrlFacadeInterface;
use Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiConfig getConfig()
 */
class MerchantProfileMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
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
     * @return \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\DataProvider\MerchantProfileFormDataProviderInterface
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
     * @return \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\DataProvider\MerchantProfileAddressFormDataProviderInterface
     */
    public function createMerchantProfileAddressFormDataProvider(): MerchantProfileAddressFormDataProviderInterface
    {
        return new MerchantProfileAddressFormDataProvider($this->getCountryFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Constraint\UniqueUrl
     */
    public function createUniqueUrlConstraint(): UniqueUrl
    {
        return new UniqueUrl();
    }

    /**
     * @return \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantProfileMerchantPortalGuiToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileMerchantPortalGuiDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantProfileMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): MerchantProfileMerchantPortalGuiToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileMerchantPortalGuiDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): MerchantProfileMerchantPortalGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileMerchantPortalGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToUrlFacadeInterface
     */
    public function getUrlFacade(): MerchantProfileMerchantPortalGuiToUrlFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileMerchantPortalGuiDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToCountryFacadeInterface
     */
    public function getCountryFacade(): MerchantProfileMerchantPortalGuiToCountryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileMerchantPortalGuiDependencyProvider::FACADE_COUNTRY);
    }
}
