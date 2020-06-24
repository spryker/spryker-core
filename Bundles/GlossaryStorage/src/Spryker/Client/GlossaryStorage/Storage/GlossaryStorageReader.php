<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageClientInterface;
use Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface;
use Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilEncodingServiceInterface;
use Spryker\Client\GlossaryStorage\GlossaryStorageConfig;
use Spryker\Client\Kernel\Locator;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConfig as SharedGlossaryStorageConfig;
use Symfony\Contracts\Translation\TranslatorTrait;

class GlossaryStorageReader implements GlossaryStorageReaderInterface
{
    use TranslatorTrait;

    protected const KEY_VALUE = 'value';
    protected const KEY_GLOSSARY_KEY = 'GlossaryKey';
    protected const KEY_KEY = 'key';

    /**
     * @var \Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var string[][]
     */
    protected static $translationsCache = [];

    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface|null
     */
    protected static $storageKeyBuilder;

    /**
     * @param \Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        GlossaryStorageToStorageClientInterface $storageClient,
        GlossaryStorageToSynchronizationServiceInterface $synchronizationService,
        GlossaryStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $keyName
     * @param string $localeName
     * @param string[] $parameters
     *
     * @return string
     */
    public function translate(string $keyName, string $localeName, array $parameters = []): string
    {
        if (GlossaryStorageConfig::isCollectorCompatibilityMode()) {
            $clientLocatorClass = Locator::class;
            $glossaryClient = $clientLocatorClass::getInstance()->glossary()->client();

            return $glossaryClient->translate($keyName, $localeName, $parameters);
        }

        if ($keyName === '') {
            return $keyName;
        }

        $translation = $this->getTranslation($keyName, $localeName);
        if (empty($parameters)) {
            return $translation;
        }

        return $this->trans($translation, $parameters, null, $localeName);
    }

    /**
     * @param string[] $keyNames
     * @param string $localeName
     * @param string[][] $parameters
     *
     * @return string[]
     */
    public function translateBulk(array $keyNames, string $localeName, array $parameters = []): array
    {
        $translations = $this->getTranslations($keyNames, $localeName);
        foreach ($translations as $keyName => &$translation) {
            if (empty($parameters[$keyName])) {
                continue;
            }

            $translation = $this->trans($translation, $parameters[$keyName], null, $localeName);
        }

        return $translations;
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return string
     */
    protected function generateStorageKey(string $keyName, string $localeName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($keyName)
            ->setLocale($localeName);

        return $this->getStorageKeyBuilder()->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected function getStorageKeyBuilder(): SynchronizationKeyGeneratorPluginInterface
    {
        if (static::$storageKeyBuilder === null) {
            static::$storageKeyBuilder = $this->synchronizationService->getStorageKeyBuilder(SharedGlossaryStorageConfig::TRANSLATION_RESOURCE_NAME);
        }

        return static::$storageKeyBuilder;
    }

    /**
     * @param string[] $keyNames
     * @param string $localeName
     *
     * @return string[]
     */
    protected function generateStorageKeys(array $keyNames, string $localeName): array
    {
        $glossaryStorageKeys = [];
        foreach ($keyNames as $keyName) {
            $glossaryStorageKeys[$keyName] = $this->generateStorageKey($keyName, $localeName);
        }

        return $glossaryStorageKeys;
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return string
     */
    protected function getTranslation(string $keyName, string $localeName): string
    {
        $translation = $this->findCachedTranslation($keyName, $localeName);
        if ($translation) {
            return $translation;
        }

        $translation = $keyName;
        $glossaryStorageDataItem = $this->storageClient->get($this->generateStorageKey($keyName, $localeName));
        if ($glossaryStorageDataItem) {
            $translation = $glossaryStorageDataItem[static::KEY_VALUE];
        }
        $this->cacheTranslation($localeName, $keyName, $translation);

        return $translation;
    }

    /**
     * @param string[] $keyNames
     * @param string $localeName
     *
     * @return string[]
     */
    protected function getTranslations(array $keyNames, string $localeName): array
    {
        $cachedTranslations = $this->getCachedTranslations($keyNames, $localeName);
        if (count($cachedTranslations) === count($keyNames)) {
            return $cachedTranslations;
        }

        $uncachedKeyNames = array_diff($keyNames, array_keys($cachedTranslations));
        $translations = $this->getTranslationsFromStorage($uncachedKeyNames, $localeName);
        $this->cacheTranslations($translations, $localeName);

        return array_merge($cachedTranslations, $translations);
    }

    /**
     * @param string[] $keyNames
     * @param string $localeName
     *
     * @return string[]
     */
    protected function getTranslationsFromStorage(array $keyNames, string $localeName): array
    {
        $glossaryStorageKeys = $this->generateStorageKeys($keyNames, $localeName);
        $translations = [];
        $glossaryStorageEncodedData = $this->storageClient->getMulti($glossaryStorageKeys);
        foreach ($glossaryStorageEncodedData as $glossaryStorageEncodedDataItem) {
            if (!$glossaryStorageEncodedDataItem) {
                continue;
            }

            $glossaryStorageDataItem = $this->utilEncodingService->decodeJson($glossaryStorageEncodedDataItem, true);
            $keyName = $glossaryStorageDataItem[static::KEY_GLOSSARY_KEY][static::KEY_KEY];
            $translations[$keyName] = $glossaryStorageDataItem[static::KEY_VALUE];
        }

        /**
         * @var string[]
         */
        $notFoundTranslations = array_combine($keyNames, $keyNames);

        return $translations + $notFoundTranslations;
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return string|null
     */
    protected function findCachedTranslation(string $keyName, string $localeName): ?string
    {
        return static::$translationsCache[$keyName][$localeName] ?? null;
    }

    /**
     * @param string[] $keyNames
     * @param string $localeName
     *
     * @return string[]
     */
    protected function getCachedTranslations(array $keyNames, string $localeName): array
    {
        return array_intersect_key(
            static::$translationsCache[$localeName] ?? [],
            array_flip($keyNames)
        );
    }

    /**
     * @param string $localeName
     * @param string $keyName
     * @param string $translation
     *
     * @return void
     */
    protected function cacheTranslation(string $localeName, string $keyName, string $translation): void
    {
        static::$translationsCache[$localeName][$keyName] = $translation;
    }

    /**
     * @param string[] $translations
     * @param string $localeName
     *
     * @return void
     */
    protected function cacheTranslations(array $translations, string $localeName): void
    {
        foreach ($translations as $keyName => $translation) {
            $this->cacheTranslation($localeName, $keyName, $translation);
        }
    }
}
