<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\ChangePasswordForm;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint\CurrentPasswordConstraint;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint\UniqueUserEmailConstraint;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\DataProvider\MerchantAccountFormDataProvider;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\DataProvider\MerchantAccountFormDataProviderInterface;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\MerchantAccountForm;
use Spryker\Zed\UserMerchantPortalGui\Communication\Updater\MerchantUserUpdater;
use Spryker\Zed\UserMerchantPortalGui\Communication\Updater\MerchantUserUpdaterInterface;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\UserMerchantPortalGui\UserMerchantPortalGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;

class UserMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createMerchantAccountForm(array $data = [], array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantAccountForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\UserMerchantPortalGui\Communication\Form\DataProvider\MerchantAccountFormDataProvider
     */
    public function createMerchantAccountFormDataProvider(): MerchantAccountFormDataProviderInterface
    {
        return new MerchantAccountFormDataProvider(
            $this->getLocaleFacade(),
            $this->getMerchantUserFacade(),
        );
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createUniqueUserEmailConstraint(): Constraint
    {
        return new UniqueUserEmailConstraint([
            UniqueUserEmailConstraint::OPTION_MERCHANT_USER_FACADE => $this->getMerchantUserFacade(),
        ]);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createChangePasswordForm(): FormInterface
    {
        return $this->getFormFactory()->create(ChangePasswordForm::class);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createCurrentPasswordConstraint(): Constraint
    {
        return new CurrentPasswordConstraint([
            CurrentPasswordConstraint::OPTION_MERCHANT_USER_FACADE => $this->getMerchantUserFacade(),
        ]);
    }

    /**
     * @return \Spryker\Zed\UserMerchantPortalGui\Communication\Updater\MerchantUserUpdaterInterface
     */
    public function createMerchantUserUpdater(): MerchantUserUpdaterInterface
    {
        return new MerchantUserUpdater(
            $this->getMerchantUserFacade(),
            $this->getMerchantUserPostChangePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): UserMerchantPortalGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(UserMerchantPortalGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): UserMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(UserMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\UserMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserPostChangePluginInterface[]
     */
    public function getMerchantUserPostChangePlugins(): array
    {
        return $this->getProvidedDependency(UserMerchantPortalGuiDependencyProvider::PLUGINS_MERCHANT_USER_POST_CHANGE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): UserMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(UserMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }
}
