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
     * @var array
     */
    protected $translations = [];

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

        if (!isset($this->translations[$keyName])) {
            $this->loadTranslation($keyName, $localeName);
        }

        if (empty($parameters)) {
            return $this->translations[$keyName];
        }

        return str_replace(
            array_keys($parameters),
            array_values($parameters),
            $this->translations[$keyName]
        );
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return void
     */
    protected function loadTranslation($keyName, $localeName)
    {
        $key = $this->generateGlossaryStorageKey($keyName, $localeName);
        $translation = $this->findTranslation($key);

        $this->addTranslation($keyName, $translation);
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return string
     */
    protected function generateGlossaryStorageKey($keyName, $localeName)
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($keyName)
            ->setLocale($localeName);

        return $this->synchronizationService
            ->getStorageKeyBuilder(GlossaryStorageConstants::RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    protected function findTranslation($key)
    {
        /** @var array|null $translation */
        $translation = $this->storageClient->get($key);
        if ($translation === null) {
            return null;
        }

        return $translation['value'];
    }

    /**
     * @param string $keyName
     * @param string|null $translation
     *
     * @return void
     */
    protected function addTranslation($keyName, $translation)
    {
        if ($translation === null) {
            $translation = $keyName;
        }
        $this->translations[$keyName] = $translation;
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
        $this->loadTranslations($keyNames, $localeName);
        $translations = [];

        foreach ($keyNames as $keyName) {
            if (empty($parameters[$keyName])) {
                $translations[$keyName] = $this->translations[$keyName];
                continue;
            }

            $translations[$keyName] = str_replace(
                array_keys($parameters[$keyName]),
                array_values($parameters[$keyName]),
                $this->translations[$keyName]
            );
        }

        return $translations;
    }

    /**
     * @param string[] $keyNames
     * @param string $localeName
     *
     * @return void
     */
    protected function loadTranslations(array $keyNames, string $localeName): void
    {
        $keyNames = array_diff_key(array_flip($keyNames), $this->translations);
        $glossaryStorageKeys = $this->generateGlossaryStorageKeys($keyNames, $localeName);
        $glossaryStorageDataItems = $this->getGlossaryStorageDataItemsByGlossaryStorageKeys($glossaryStorageKeys);
        $glossaryStorageTransfers = $this->glossaryStorageMapper->mapGlossaryStorageDataItemsToGlossaryStorageTransfers(
            $glossaryStorageDataItems
        );
        $this->addTranslations($glossaryStorageTransfers, $keyNames);
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
            if (!$glossaryStorageEncodedDataItem) {
                $glossaryStorageDataItems[$glossaryStorageKey] = null;
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
     *
     * @return void
     */
    protected function addTranslations(array $glossaryStorageTransfers, array $keyNames): void
    {
        foreach ($glossaryStorageTransfers as $glossaryStorageTransfer) {
            $this->addTranslation(
                $glossaryStorageTransfer->getGlossaryKey()->getKey(),
                $glossaryStorageTransfer->getValue()
            );
        }

        foreach (array_diff_key(array_flip($keyNames), $this->translations) as $keyName) {
            $this->addTranslation($keyName, null);
        }
    }
}
