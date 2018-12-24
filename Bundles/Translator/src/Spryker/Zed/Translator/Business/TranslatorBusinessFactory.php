<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Translator\Business\Cache\CacheClearer;
use Spryker\Zed\Translator\Business\Cache\CacheClearerInterface;
use Spryker\Zed\Translator\Business\Finder\TranslationFinder;
use Spryker\Zed\Translator\Business\Finder\TranslationFinderInterface;
use Spryker\Zed\Translator\Business\Translator\Translator;
use Spryker\Zed\Translator\Business\Translator\TranslatorInterface;
use Spryker\Zed\Translator\TranslatorDependencyProvider;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\Loader\CsvFileLoader;
use Symfony\Component\Translation\Loader\XliffFileLoader;

/**
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 */
class TranslatorBusinessFactory extends AbstractBusinessFactory
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
     * @return \Symfony\Component\Translation\Loader\XliffFileLoader
     */
    public function createXlfFileLoader(): XliffFileLoader
    {
        return new XliffFileLoader();
    }

    /**
     * @return \Spryker\Zed\Translator\Business\Translator\TranslatorInterface
     */
    public function createTranslator(): TranslatorInterface
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
        $translator->addLoader('xlf', $this->createXlfFileLoader());
        $locales = $this->getStore()->getLocales();
        foreach ($locales as $country => $locale) {
            $translator->addResource('xlf', $this->getConfig()->getValidatorsTranslationPath($country), $locale, 'validators');
        }

        return $translator;
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    protected function getApplication(): Application
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::APPLICATION);
    }

    /**
     * @return \Spryker\Zed\Translator\Business\Cache\CacheClearerInterface
     */
    public function createCacheClearer(): CacheClearerInterface
    {
        return new CacheClearer(
            $this->getConfig(),
            $this->getFileSystem(),
            $this->getFinder()
        );
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    protected function getFileSystem(): Filesystem
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::SYMFONY_FILE_SYSTEM);
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder(): Finder
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::SYMFONY_FINDER);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::STORE);
    }
}
