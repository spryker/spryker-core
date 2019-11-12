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
use Spryker\Client\GlossaryStorage\Processor\Mapper\GlossaryStorageMapperInterface;
use Spryker\Client\Kernel\Locator;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConstants;

class GlossaryStorage implements GlossaryStorageInterface
{
    protected const KEY_VALUE = 'value';

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
     * @var \Spryker\Client\GlossaryStorage\Processor\Mapper\GlossaryStorageMapperInterface
     */
    protected $glossaryStorageMapper;

    /**
     * @var string[][]
     */
    protected $translationsCache = [];

    /**
     * @param \Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\GlossaryStorage\Processor\Mapper\GlossaryStorageMapperInterface $glossaryStorageMapper
     */
    public function __construct(
        GlossaryStorageToStorageClientInterface $storageClient,
        GlossaryStorageToSynchronizationServiceInterface $synchronizationService,
        GlossaryStorageToUtilEncodingServiceInterface $utilEncodingService,
        GlossaryStorageMapperInterface $glossaryStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->utilEncodingService = $utilEncodingService;
        $this->glossaryStorageMapper = $glossaryStorageMapper;
    }

    /**
     * @param string $keyName
     * @param string $localeName
     * @param array $parameters
     *
     * @return string
     */
    public function translate($keyName, $localeName, array $parameters = [])
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

        return str_replace(
            array_keys($parameters),
            array_values($parameters),
            $translation
        );
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return string
     */
    protected function generateGlossaryStorageKey(string $keyName, string $localeName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($keyName)
            ->setLocale($localeName);

        return $this->synchronizationService
            ->getStorageKeyBuilder(GlossaryStorageConstants::RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return string
     */
    protected function getTranslation(string $keyName, string $localeName): string
    {
        if (isset($this->translationsCache[$keyName][$localeName])) {
            return $this->translationsCache[$keyName][$localeName];
        }

        $translation = $keyName;
        $glossaryStorageDataItem = $this->storageClient->get($this->generateGlossaryStorageKey($keyName, $localeName));
        if ($glossaryStorageDataItem) {
            $translation = $glossaryStorageDataItem[static::KEY_VALUE];
        }
        $this->cacheTranslation($localeName, $keyName, $translation);

        return $translation;
    }

    /**
     * @param string[] $keyNames
     * @param string $localeName
     * @param array $parameters
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

            $translation = str_replace(
                array_keys($parameters[$keyName]),
                array_values($parameters[$keyName]),
                $translation
            );
        }

        return $translations;
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
        $glossaryStorageKeys = $this->generateGlossaryStorageKeys($uncachedKeyNames, $localeName);
        $glossaryStorageDataItems = $this->getGlossaryStorageDataItemsByGlossaryStorageKeys($glossaryStorageKeys);
        $glossaryStorageTransfers = $this->glossaryStorageMapper->mapGlossaryStorageDataItemsToGlossaryStorageTransfers(
            $glossaryStorageDataItems
        );
        $this->cacheTranslations($glossaryStorageTransfers, $keyNames, $localeName);

        return $this->getCachedTranslations($keyNames, $localeName);
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
            $this->translationsCache[$localeName] ?? [],
            array_flip($keyNames)
        );
    }

    /**
     * @param string[] $keyNames
     * @param string $localeName
     *
     * @return string[]
     */
    protected function generateGlossaryStorageKeys(array $keyNames, string $localeName): array
    {
        $glossaryStorageKeys = [];
        foreach ($keyNames as $keyName) {
            $glossaryStorageKeys[$keyName] = $this->generateGlossaryStorageKey($keyName, $localeName);
        }

        return $glossaryStorageKeys;
    }

    /**
     * @param array $glossaryStorageKeys
     *
     * @return array
     */
    protected function getGlossaryStorageDataItemsByGlossaryStorageKeys(array $glossaryStorageKeys): array
    {
        $glossaryStorageDataItems = [];
        $glossaryStorageEncodedData = $this->storageClient->getMulti($glossaryStorageKeys);
        foreach ($glossaryStorageEncodedData as $glossaryStorageKey => $glossaryStorageEncodedDataItem) {
            $glossaryStorageDataItems[$glossaryStorageKey] = null;
            if (!$glossaryStorageEncodedDataItem) {
                continue;
            }

            $glossaryStorageDataItems[$glossaryStorageKey] = $this->utilEncodingService->decodeJson(
                $glossaryStorageEncodedDataItem,
                true
            );
        }

        return $glossaryStorageDataItems;
    }

    /**
     * @param \Generated\Shared\Transfer\GlossaryStorageTransfer[] $glossaryStorageTransfers
     * @param string[] $keyNames
     * @param string $localeName
     *
     * @return void
     */
    protected function cacheTranslations(array $glossaryStorageTransfers, array $keyNames, string $localeName): void
    {
        foreach ($glossaryStorageTransfers as $glossaryStorageTransfer) {
            $this->cacheTranslation(
                $localeName,
                $glossaryStorageTransfer->getGlossaryKey()->getKey(),
                $glossaryStorageTransfer->getValue()
            );
        }

        $notFoundKeyNames = array_diff($keyNames, array_keys($this->getCachedTranslations($keyNames, $localeName)));
        foreach ($notFoundKeyNames as $keyName) {
            $this->cacheTranslation($localeName, $keyName, $keyName);
        }
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
        $this->translationsCache[$localeName][$keyName] = $translation;
    }
}
