<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Translator\Business\TranslationCache\CacheCleaner;
use Spryker\Zed\Translator\Business\TranslationCache\CacheCleanerInterface;
use Spryker\Zed\Translator\Business\TranslationCache\CacheGenerator;
use Spryker\Zed\Translator\Business\TranslationCache\CacheGeneratorInterface;
use Spryker\Zed\Translator\Business\TranslationFinder\TranslationFileFinder;
use Spryker\Zed\Translator\Business\TranslationFinder\TranslationFileFinderInterface;
use Spryker\Zed\Translator\Business\TranslationLoader\CsvFileLoader;
use Spryker\Zed\Translator\Business\TranslationLoader\TranslationLoaderInterface;
use Spryker\Zed\Translator\Business\TranslationLoader\XliffLoader;
use Spryker\Zed\Translator\Business\TranslationResource\CsvResourceFileLoader;
use Spryker\Zed\Translator\Business\TranslationResource\TranslationResourceFileLoaderInterface;
use Spryker\Zed\Translator\Business\TranslationResource\ValidatorResourceFileLoader;
use Spryker\Zed\Translator\Business\Translator\Translator;
use Spryker\Zed\Translator\Business\Translator\TranslatorInterface;
use Spryker\Zed\Translator\Business\Translator\TranslatorPreparator;
use Spryker\Zed\Translator\Business\Translator\TranslatorPreparatorInterface;
use Spryker\Zed\Translator\Business\TranslatorBuilder\TranslatorBuilder;
use Spryker\Zed\Translator\Business\TranslatorBuilder\TranslatorBuilderInterface;
use Spryker\Zed\Translator\Dependency\Facade\TranslatorToLocaleFacadeInterface;
use Spryker\Zed\Translator\TranslatorDependencyProvider;

/**
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 */
class TranslatorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Translator\Business\TranslationFinder\TranslationFileFinderInterface
     */
    public function createTranslationFileFinder(): TranslationFileFinderInterface
    {
        return new TranslationFileFinder();
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslationResource\TranslationResourceFileLoaderInterface[]
     */
    public function getTranslationResourceFileLoaderCollection(): array
    {
        return [
            $this->createCsvResourceFileLoader(),
            $this->createValidatorResourceFileLoader(),
        ];
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslationResource\TranslationResourceFileLoaderInterface
     */
    public function createCsvResourceFileLoader(): TranslationResourceFileLoaderInterface
    {
        return new CsvResourceFileLoader(
            $this->createCsvFileLoader(),
            $this->createTranslationFileFinder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslationResource\TranslationResourceFileLoaderInterface
     */
    public function createValidatorResourceFileLoader(): TranslationResourceFileLoaderInterface
    {
        return new ValidatorResourceFileLoader(
            $this->createCsvFileLoader(),
            $this->createTranslationFileFinder(),
            $this->getConfig(),
            $this->getStore()->getLocales()
        );
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslationLoader\TranslationLoaderInterface
     */
    public function createCsvFileLoader(): TranslationLoaderInterface
    {
        $csvFileLoader = new CsvFileLoader();
        $csvFileLoader->setCsvControl($this->getConfig()->getCsvFileDelimiter());

        return $csvFileLoader;
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslationLoader\TranslationLoaderInterface
     */
    public function createXlfFileLoader(): TranslationLoaderInterface
    {
        return new XliffLoader();
    }

    /**
     * @return \Spryker\Zed\Translator\Business\Translator\TranslatorInterface[]
     */
    public function createTranslatorCollection(): array
    {
        $availableLocales = $this->getLocaleFacade()->getAvailableLocalesAsString();

        return array_map(function ($availableLocale) {
            return $this->createTranslator($availableLocale);
        }, $availableLocales);
    }

    /**
     * @param string|null $localeName
     *
     * @return \Spryker\Zed\Translator\Business\Translator\TranslatorInterface
     */
    public function createTranslator(?string $localeName = null): TranslatorInterface
    {
        $localeName = $localeName ?? $this->getLocaleFacade()->getCurrentLocaleName();
        $translator = new Translator(
            $this->createTranslationBuilder(),
            $localeName,
            $this->getConfig()
        );

        return $translator;
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslatorBuilder\TranslatorBuilderInterface
     */
    public function createTranslationBuilder(): TranslatorBuilderInterface
    {
        return new TranslatorBuilder(
            $this->getTranslationResourceFileLoaderCollection()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::APPLICATION);
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslationCache\CacheCleanerInterface
     */
    public function createTranslationCacheCleaner(): CacheCleanerInterface
    {
        return new CacheCleaner(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslationCache\CacheGeneratorInterface
     */
    public function createTranslationCacheGenerator(): CacheGeneratorInterface
    {
        return new CacheGenerator(
            $this->createTranslatorCollection()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::STORE);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Translator\Business\Translator\TranslatorPreparatorInterface
     */
    public function createTranslatorPreparator(): TranslatorPreparatorInterface
    {
        return new TranslatorPreparator(
            $this->getApplication(),
            $this->createTranslator()
        );
    }

    /**
     * @return \Spryker\Zed\Translator\Dependency\Facade\TranslatorToLocaleFacadeInterface
     */
    public function getLocaleFacade(): TranslatorToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::FACADE_LOCALE);
    }
}
