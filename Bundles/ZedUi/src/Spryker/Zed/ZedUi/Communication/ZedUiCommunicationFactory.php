<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedUi\Communication;

use Spryker\Shared\Twig\TwigFunctionProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ZedUi\Communication\Twig\NavigationComponentConfigFunctionProvider;
use Spryker\Zed\ZedUi\Dependency\Facade\ZedUiToTranslatorFacadeInterface;
use Spryker\Zed\ZedUi\Dependency\Service\ZedUiToUtilEncodingServiceInterface;
use Spryker\Zed\ZedUi\ZedUiDependencyProvider;
use Twig\TwigFunction;

class ZedUiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Twig\TwigFunctionProvider
     */
    public function createNavigationComponentConfigFunctionProvider(): TwigFunctionProvider
    {
        return new NavigationComponentConfigFunctionProvider(
            $this->getUtilEncoding(),
            $this->getTranslatorFacade()
        );
    }

    /**
     * @return \Twig\TwigFunction
     */
    public function createNavigationComponentConfigFunction(): TwigFunction
    {
        $functionProvider = $this->createNavigationComponentConfigFunctionProvider();

        return new TwigFunction(
            $functionProvider->getFunctionName(),
            $functionProvider->getFunction(),
            $functionProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\ZedUi\Dependency\Service\ZedUiToUtilEncodingServiceInterface
     */
    public function getUtilEncoding(): ZedUiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ZedUiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ZedUi\Dependency\Facade\ZedUiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): ZedUiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(ZedUiDependencyProvider::FACADE_TRANSLATOR);
    }
}
