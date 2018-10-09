<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Communication;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Translator\Business\Finder\TranslationFinder;
use Spryker\Zed\Translator\Business\Finder\TranslationFinderInterface;
use Spryker\Zed\Translator\Business\Translator\Translator;
use Spryker\Zed\Translator\TranslatorDependencyProvider;
use Symfony\Component\Translation\Loader\CsvFileLoader;

/**
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 */
class TranslatorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Translator\Business\Finder\TranslationFinderInterface
     */
    public function createTranslationFinder(): TranslationFinderInterface
    {
        return new TranslationFinder($this->getConfig());
    }

    /**
     * @return \Symfony\Component\Translation\Loader\CsvFileLoader
     */
    public function createCsvFileLoader(): CsvFileLoader
    {
        $csvFileLoader = new CsvFileLoader();
        $csvFileLoader->setCsvControl($this->getConfig()->getDelimiter());

        return $csvFileLoader;
    }

    /**
     * @return \Spryker\Zed\Translator\Business\Translator\TranslatorInterface
     */
    public function createTranslator()
    {
        $translator = new Translator(
            $this->getApplication()['locale'],
            null,
            $this->getConfig()->getCacheDir()
        );
        $translationFinder = $this->createTranslationFinder();
        $translator->setLazyLoadResources($translationFinder);
        $translator->addLoader($translationFinder->getFileFormat(), $this->createCsvFileLoader());
        $translator->setFallbackLocales($this->getConfig()->getFallbackLocales($this->getApplication()['locale']));

        return $translator;
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    protected function getApplication(): Application
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::APPLICATION);
    }
}
