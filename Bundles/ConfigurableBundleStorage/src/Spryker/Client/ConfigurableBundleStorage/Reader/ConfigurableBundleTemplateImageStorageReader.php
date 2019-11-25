<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateImageStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToLocaleClientInterface;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToStorageClientInterface;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToSynchronizationServiceInterface;
use Spryker\Shared\ConfigurableBundleStorage\ConfigurableBundleStorageConfig;

class ConfigurableBundleTemplateImageStorageReader implements ConfigurableBundleTemplateImageStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToLocaleClientInterface $localeClient
     */
    public function __construct(
        ConfigurableBundleStorageToStorageClientInterface $storageClient,
        ConfigurableBundleStorageToSynchronizationServiceInterface $synchronizationService,
        ConfigurableBundleStorageToLocaleClientInterface $localeClient
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->localeClient = $localeClient;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateImageStorageTransfer|null
     */
    public function findConfigurableBundleTemplateImageStorage(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateImageStorageTransfer
    {
        $configurableBundleTemplateImageStorageData = $this->storageClient->get(
            $this->generateKey($idConfigurableBundleTemplate, $this->localeClient->getCurrentLocale())
        );

        if (!$configurableBundleTemplateImageStorageData) {
            return null;
        }

        return $this->mapToConfigurableBundleImageStorage($configurableBundleTemplateImageStorageData);
    }

    /**
     * @param array $configurableBundleTemplateImageStorageData
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateImageStorageTransfer
     */
    protected function mapToConfigurableBundleImageStorage(array $configurableBundleTemplateImageStorageData): ConfigurableBundleTemplateImageStorageTransfer
    {
        return (new ConfigurableBundleTemplateImageStorageTransfer())
            ->fromArray($configurableBundleTemplateImageStorageData, true);
    }

    /**
     * @param int $idConfigurableBundleTemplate
     * @param string $locale
     *
     * @return string
     */
    protected function generateKey(int $idConfigurableBundleTemplate, string $locale): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setLocale($locale)
            ->setReference((string)$idConfigurableBundleTemplate);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ConfigurableBundleStorageConfig::CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
