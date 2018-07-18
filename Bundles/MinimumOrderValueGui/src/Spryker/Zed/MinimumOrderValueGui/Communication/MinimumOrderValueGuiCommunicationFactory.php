<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdDataProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiConfig getConfig()
 */
class MinimumOrderValueGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createGlobalThresholdForm(Request $request): FormInterface
    {
        $formDataProvider = $this->createStoreDataProvider();

        return $this->getFormFactory()->create(
            GlobalThresholdType::class,
            $formDataProvider->getData($request),
            $formDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdDataProvider
     */
    public function createStoreDataProvider(): GlobalThresholdDataProvider
    {
        return new GlobalThresholdDataProvider(
            $this->getCurrencyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): MinimumOrderValueGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueGuiDependencyProvider::FACADE_CURRENCY);
    }
}
