<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider\MerchantUpdateFormDataProvider;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\MerchantForm;
use Spryker\Zed\MerchantProfileGuiPage\MerchantProfileGuiPageDependencyProvider;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToMerchantFacadeInterface;
use Symfony\Component\Form\FormInterface;

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
     * @return \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantProfileGuiPageToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileGuiPageDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormExpanderPluginInterface[]
     */
    public function getMerchantProfileFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantProfileGuiPageDependencyProvider::PLUGINS_MERCHANT_PROFILE_FORM_EXPANDER);
    }
}
