<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Translator;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Translator\Dependency\Client\TranslatorToGlossaryStorageClientInterface;
use Spryker\Yves\Translator\Dependency\Client\TranslatorToLocaleClientInterface;
use Spryker\Yves\Translator\Translator\Translator;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Twig\Extension\AbstractExtension;

class TranslatorFactory extends AbstractFactory
{
    /**
     * @return \Twig\Extension\AbstractExtension
     */
    public function createTwigTranslationExtension(): AbstractExtension
    {
        return new TranslationExtension($this->createTranslator());
    }

    /**
     * @return \Symfony\Component\Translation\TranslatorInterface|\Symfony\Contracts\Translation\TranslatorInterface
     */
    public function createTranslator()
    {
        return new Translator(
            $this->getGlossaryStorageClient(),
            $this->getLocaleClient()
        );
    }

    /**
     * @return \Spryker\Yves\Translator\Dependency\Client\TranslatorToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): TranslatorToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \Spryker\Yves\Translator\Dependency\Client\TranslatorToLocaleClientInterface
     */
    public function getLocaleClient(): TranslatorToLocaleClientInterface
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::CLIENT_LOCALE);
    }
}
