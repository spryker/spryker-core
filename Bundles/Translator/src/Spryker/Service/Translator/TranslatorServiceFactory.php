<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Translator\TranslationCache\CacheCleaner;
use Spryker\Service\Translator\TranslationCache\CacheCleanerInterface;
use Spryker\Service\Translator\TranslationCache\CacheGenerator;
use Spryker\Service\Translator\TranslationCache\CacheGeneratorInterface;
use Spryker\Service\Translator\TranslationFinder\TranslationFileFinder;
use Spryker\Service\Translator\TranslationFinder\TranslationFileFinderInterface;
use Spryker\Service\Translator\TranslationKeyManager\TranslationKeyManager;
use Spryker\Service\Translator\TranslationKeyManager\TranslationKeyManagerInterface;
use Spryker\Service\Translator\TranslationLoader\CsvFileLoader;
use Spryker\Service\Translator\TranslationLoader\TranslationLoaderInterface;
use Spryker\Service\Translator\TranslationLoader\XliffLoader;
use Spryker\Service\Translator\TranslationResource\CsvResourceFileLoader;
use Spryker\Service\Translator\TranslationResource\TranslationResourceFileLoaderInterface;
use Spryker\Service\Translator\TranslationResource\ValidatorResourceFileLoader;
use Spryker\Service\Translator\Translator\Translator;
use Spryker\Service\Translator\TranslatorBuilder\TranslatorBuilder;
use Spryker\Service\Translator\TranslatorBuilder\TranslatorBuilderInterface;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Shared\Kernel\Store;

/**
 * @method \Spryker\Service\Translator\TranslatorConfig getConfig()()
 */
class TranslatorServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Translator\TranslationFinder\TranslationFileFinderInterface
     */
    public function createTranslationFileFinder(): TranslationFileFinderInterface
    {
        return new TranslationFileFinder();
    }

    /**
     * @return \Spryker\Service\Translator\TranslationResource\TranslationResourceFileLoaderInterface[]
     */
    public function getTranslationResourceFileLoaderCollection(): array
    {
        return [
            $this->createCsvResourceFileLoader(),
            $this->createValidatorResourceFileLoader(),
        ];
    }

    /**
     * @return \Spryker\Service\Translator\TranslationResource\TranslationResourceFileLoaderInterface
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
     * @return \Spryker\Service\Translator\TranslationResource\TranslationResourceFileLoaderInterface
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
     * @return \Spryker\Service\Translator\TranslationLoader\TranslationLoaderInterface
     */
    public function createCsvFileLoader(): TranslationLoaderInterface
    {
        $csvFileLoader = new CsvFileLoader();
        $csvFileLoader->setCsvControl($this->getConfig()->getCsvDelimiter());

        return $csvFileLoader;
    }

    /**
     * @return \Spryker\Service\Translator\TranslationLoader\TranslationLoaderInterface
     */
    public function createXlfFileLoader(): TranslationLoaderInterface
    {
        return new XliffLoader();
    }

    /**
     * @param string|null $localeName
     *
     * @return \Symfony\Component\Translation\TranslatorInterface|\Symfony\Component\Translation\TranslatorBagInterface| \Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface
     */
    public function createTranslator(string $localeName = null)
    {
        $localeName = $localeName ?? $this->getApplication()['locale'];
        $translator = new Translator($localeName, null, $this->getConfig()->getCacheDirectory());
        $translator->setFallbackLocales($this->getConfig()->getFallbackLocales($localeName));

        return $this->createTranslationBuilder()->buildTranslator($translator);
    }

    /**
     * @return \Spryker\Service\Translator\TranslatorBuilder\TranslatorBuilderInterface
     */
    public function createTranslationBuilder(): TranslatorBuilderInterface
    {
        return new TranslatorBuilder(
            $this->getTranslationResourceFileLoaderCollection()
        );
    }

    /**
     * @return \Spryker\Service\Translator\TranslationKeyManager\TranslationKeyManagerInterface
     */
    public function createTranslationKeyManager(): TranslationKeyManagerInterface
    {
        return new TranslationKeyManager($this->createTranslator());
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::APPLICATION);
    }

    /**
     * @return \Spryker\Service\Translator\TranslationCache\CacheCleanerInterface
     */
    public function createCacheCleaner(): CacheCleanerInterface
    {
        return new CacheCleaner(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\Translator\TranslationCache\CacheGeneratorInterface
     */
    public function createCacheGenerator(): CacheGeneratorInterface
    {
        return new CacheGenerator(
            $this->createTranslator(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(TranslatorDependencyProvider::STORE);
    }
}
