<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthenticationMerchantPortalGui\Communication;

use Spryker\Zed\AuthenticationMerchantPortalGui\AuthenticationMerchantPortalGuiDependencyProvider;
use Spryker\Zed\AuthenticationMerchantPortalGui\Communication\Form\MerchantLoginForm;
use Spryker\Zed\AuthenticationMerchantPortalGui\Dependency\Facade\AuthenticationMerchantPortalGuiToAuthFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\AuthenticationMerchantPortalGui\AuthenticationMerchantPortalGuiConfig getConfig()
 */
class AuthenticationMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createLoginForm(): FormInterface
    {
        return $this->getFormFactory()->create(MerchantLoginForm::class);
    }

    /**
     * @return \Spryker\Zed\AuthenticationMerchantPortalGui\Dependency\Facade\AuthenticationMerchantPortalGuiToAuthFacadeInterface
     */
    public function getAuthFacade(): AuthenticationMerchantPortalGuiToAuthFacadeInterface
    {
        return $this->getProvidedDependency(AuthenticationMerchantPortalGuiDependencyProvider::FACADE_AUTH);
    }
}
