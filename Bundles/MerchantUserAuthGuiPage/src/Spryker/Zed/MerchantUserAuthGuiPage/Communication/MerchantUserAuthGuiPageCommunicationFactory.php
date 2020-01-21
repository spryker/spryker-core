<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserAuthGuiPage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantUserAuthGuiPage\Communication\Form\LoginForm;
use Spryker\Zed\MerchantUserAuthGuiPage\Dependency\Facade\MerchantUserAuthGuiPageToAuthInterface;
use Spryker\Zed\MerchantUserAuthGuiPage\MerchantUserAuthGuiPageDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MerchantUserAuthGuiPage\MerchantUserAuthGuiPageConfig getConfig()
 */
class MerchantUserAuthGuiPageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createLoginForm(): FormInterface
    {
        return $this->getFormFactory()->create(LoginForm::class);
    }

    /**
     * @return \Spryker\Zed\MerchantUserAuthGuiPage\Dependency\Facade\MerchantUserAuthGuiPageToAuthInterface
     */
    public function getAuthFacade(): MerchantUserAuthGuiPageToAuthInterface
    {
        return $this->getProvidedDependency(MerchantUserAuthGuiPageDependencyProvider::FACADE_AUTH);
    }
}
