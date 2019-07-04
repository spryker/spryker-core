<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Communication;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Shared\TranslatorExtension\Dependency\Plugin\TranslatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Translator\Dependency\Facade\TranslatorToLocaleFacadeInterface;
use Spryker\Zed\Translator\TranslatorDependencyProvider;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Twig\Extension\AbstractExtension;

/**
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 * @method \Spryker\Zed\Translator\Business\TranslatorFacadeInterface getFacade()
 */
class TranslatorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::APPLICATION);
    }

    /**
     * @return \Spryker\Zed\Translator\Dependency\Facade\TranslatorToLocaleFacadeInterface
     */
    public function getLocaleFacade(): TranslatorToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Twig\Extension\AbstractExtension
     */
    public function createTwigTranslationExtension(): AbstractExtension
    {
        return new TranslationExtension($this->getTranslatorPlugin());
    }

    /**
     * @return \Spryker\Shared\TranslatorExtension\Dependency\Plugin\TranslatorPluginInterface
     */
    public function getTranslatorPlugin(): TranslatorPluginInterface
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::PLUGIN_TRANSLATOR);
    }
}
