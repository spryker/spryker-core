<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage\Storage;

use ArrayObject;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\Kernel\Locator;
use Spryker\Client\UrlStorage\Dependency\Client\UrlStorageToStorageInterface;
use Spryker\Client\UrlStorage\Dependency\Service\UrlStorageToSynchronizationServiceInterface;
use Spryker\Client\UrlStorage\UrlStorageConfig;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\VarDumper\VarDumper;

class UrlStorageReader implements UrlStorageReaderInterface
{
    public const URL = 'url';

    /**
     * @var \Spryker\Client\UrlStorage\Dependency\Client\UrlStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\UrlStorage\Dependency\Service\UrlStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface[]
     */
    protected $urlStorageResourceMapperPlugins;

    /**
     * @param \Spryker\Client\UrlStorage\Dependency\Client\UrlStorageToStorageInterface $storageClient
     * @param \Spryker\Client\UrlStorage\Dependency\Service\UrlStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface[] $resourceMapperPlugins
     */
    public function __construct(UrlStorageToStorageInterface $storageClient, UrlStorageToSynchronizationServiceInterface $synchronizationService, array $resourceMapperPlugins)
    {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->urlStorageResourceMapperPlugins = $resourceMapperPlugins;
    }

    /**
     * @param string $url
     * @param string $localeName
     *
     * @return array
     */
    public function matchUrl($url, $localeName)
    {
        if (UrlStorageConfig::isCollectorCompatibilityMode()) {
            /** @var UrlClientInterface $urlClient */
            $urlClient = Locator::getInstance()->url()->client();

            return $urlClient->matchUrl($url, $localeName);
        }

        $urlDetails = $this->getUrlFromStorage($url);
        if (!$urlDetails) {
            return [];
        }

        $options = [
            'locale' => strtolower($localeName),
        ];
        $urlStorageResourceMapTransfer = $this->getUrlStorageResourceMapTransfer($urlDetails, $options);
        if ($urlStorageResourceMapTransfer === null) {
            return [];
        }

        $data = $this->storageClient->get($urlStorageResourceMapTransfer->getResourceKey());
        if ($data) {
            return [
                'type' => $urlStorageResourceMapTransfer->getType(),
                'data' => $data,
            ];
        }

        return [];
    }

    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlStorageTransfer|null
     */
    public function findUrlStorageTransferByUrl($url)
    {
        // NOT TESTED
        if (UrlStorageConfig::isCollectorCompatibilityMode()) {
            /** @var UrlClientInterface $urlClient */
            $urlClient = Locator::getInstance()->url()->client();
            $localeName = Store::getInstance()->getCurrentLocale();
            $urlCollectorStorageTransfer = $urlClient->findUrl($url, $localeName);

            if (!$urlCollectorStorageTransfer) {
                return null;
            }

            /** @var LocaleFacadeInterface $localeFacade */
            $localeFacade = Locator::getInstance()->locale()->facade();
            $localeTransfer = $localeFacade->getLocale($localeName);

            $primaryUrlTransfer = null;
            $urlStorageLocaleUrlTransferCollection = new ArrayObject();
            foreach ($urlCollectorStorageTransfer->getLocaleUrls() as $localeUrlTransfer) {
                $urlStorageTransfer = new UrlStorageTransfer();
                $urlStorageTransfer->fromArray($localeUrlTransfer->toArray(), true);
                $urlStorageLocaleUrlTransferCollection->append($urlStorageTransfer);
                if ($localeUrlTransfer->getFkLocale() === $localeTransfer->getIdLocale()) {
                    $primaryUrlTransfer = $localeUrlTransfer;
                }
            }

            if (!$primaryUrlTransfer) {
                return null;
            }

            echo '<pre>' . PHP_EOL . VarDumper::dump($urlCollectorStorageTransfer) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();

            $urlStorageTransfer = new UrlStorageTransfer();
            $urlStorageTransfer->fromArray($primaryUrlTransfer->toArray(), true);
            $urlStorageTransfer->setLocaleUrls($urlStorageLocaleUrlTransferCollection);

            return $urlStorageTransfer;
        }

        $urlDetails = $this->getUrlFromStorage($url);
        if (!$urlDetails) {
            return null;
        }

        return (new UrlStorageTransfer())->fromArray($urlDetails, true);
    }

    /**
     * @param string $url
     *
     * @return array
     */
    protected function getUrlFromStorage($url)
    {
        $urlKey = $this->getUrlKey($url);
        return $this->storageClient->get($urlKey);
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function getUrlKey($url)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference(rawurldecode($url));

        return $this->synchronizationService->getStorageKeyBuilder(static::URL)->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param array $urlDetails
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\UrlStorageResourceMapTransfer|null
     */
    protected function getUrlStorageResourceMapTransfer(array $urlDetails, array $options = [])
    {
        $spyUrlTransfer = new UrlStorageTransfer();
        $spyUrlTransfer->fromArray($urlDetails, true);

        foreach ($this->urlStorageResourceMapperPlugins as $urlStorageResourceMapperPlugin) {
            $pluginUrlStorageResourceMapTransfer = $urlStorageResourceMapperPlugin->map($spyUrlTransfer, $options);
            if (!empty($pluginUrlStorageResourceMapTransfer->getResourceKey())) {
                return $pluginUrlStorageResourceMapTransfer;
            }
        }

        return null;
    }
}
