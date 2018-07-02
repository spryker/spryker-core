<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageClientInterface;
use Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface;
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
     * @var array
     */
    protected $translations = [];

    /**
     * @param \Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(GlossaryStorageToStorageClientInterface $storageClient, GlossaryStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
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
        if ((string)$keyName === '') {
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
        $key = $this->generateKey($keyName, $localeName);
        $translation = $this->getTranslation($key);

        $this->addTranslation($keyName, $translation);
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return string
     */
    protected function generateKey($keyName, $localeName)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference($keyName);
        $synchronizationDataTransfer->setLocale($localeName);

        return $this->synchronizationService->getStorageKeyBuilder(GlossaryStorageConstants::RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param string $key
     *
     * @return null|string
     */
    protected function getTranslation($key)
    {
        $translation = $this->storageClient->get($key);
        if ($translation === null) {
            return null;
        }

        return $translation['value'] ?? '';
    }

    /**
     * @param string $keyName
     * @param string $translation
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
}
