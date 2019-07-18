<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlCollector\Business\Collector\Storage;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlCollectorStorageTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use RuntimeException;
use Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Url\UrlConfig;
use Spryker\Zed\UrlCollector\Dependency\QueryContainer\UrlCollectorToUrlQueryContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UrlCollector extends AbstractStoragePropelCollector
{
    public const FK_RESOURCE_ = 'fk_resource_';
    public const RESOURCE_VALUE = 'value';
    public const RESOURCE_TYPE = 'type';
    public const KEYS_RESOURCE_TYPE_SUFFIX = ' keys';

    /**
     * @var \Spryker\Zed\UrlCollector\Dependency\QueryContainer\UrlCollectorToUrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var int
     */
    protected $chunkSize = 2000;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\UrlCollector\Dependency\QueryContainer\UrlCollectorToUrlQueryContainerInterface $urlQueryContainer
     */
    public function __construct(UtilDataReaderServiceInterface $utilDataReaderService, UrlCollectorToUrlQueryContainerInterface $urlQueryContainer)
    {
        $this->urlQueryContainer = $urlQueryContainer;
        parent::__construct($utilDataReaderService);
    }

    /**
     * @param array $collectedSet
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     *
     * @return array
     */
    protected function collectData(array $collectedSet, LocaleTransfer $locale, TouchUpdaterSet $touchUpdaterSet)
    {
        $localeUrls = $this->findLocaleUrls($collectedSet);

        foreach ($collectedSet as $key => $url) {
            $urlResource = $this->findResourceArguments($url);
            $url[UrlCollectorStorageTransfer::LOCALE_URLS] = $this->getLocaleUrlsForUrl($localeUrls[$urlResource[static::RESOURCE_TYPE]], $urlResource);
            $collectedSet[$key] = $url;
        }

        return parent::collectData($collectedSet, $locale, $touchUpdaterSet);
    }

    /**
     * @param array $urls
     *
     * @return array
     */
    protected function findLocaleUrls(array $urls)
    {
        $localeUrls = [];
        foreach ($urls as $url) {
            $resourceArguments = $this->findResourceArguments($url);
            if (isset($localeUrls[$resourceArguments[static::RESOURCE_TYPE]])) {
                $localeUrls[$resourceArguments[static::RESOURCE_TYPE]][] = $resourceArguments[static::RESOURCE_VALUE];
                continue;
            }

            $localeUrls[$resourceArguments[static::RESOURCE_TYPE]] = [$resourceArguments[static::RESOURCE_VALUE]];
        }

        return $this->findLocaleUrlsFromDb($localeUrls);
    }

    /**
     * @param array $localeUrls
     *
     * @return array
     */
    protected function findLocaleUrlsFromDb(array $localeUrls)
    {
        foreach ($localeUrls as $resourceType => $resourceIds) {
            $localeUrls[$resourceType] = $this->urlQueryContainer
                ->queryUrlsByResourceTypeAndIds($resourceType, $resourceIds)
                ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                ->find()
                ->getData();
        }

        return $localeUrls;
    }

    /**
     * @param array $localeUrls
     * @param array $urlResourceArguments
     *
     * @return \Generated\Shared\Transfer\UrlStorageTransfer[]
     */
    protected function getLocaleUrlsForUrl(array $localeUrls, array $urlResourceArguments)
    {
        $siblingUrls = [];
        foreach ($localeUrls as $localeUrl) {
            $resourceArguments = $this->findResourceArguments($localeUrl);
            if ($urlResourceArguments[static::RESOURCE_VALUE] === $resourceArguments[static::RESOURCE_VALUE]) {
                $siblingUrls[] = $localeUrl;
            }
        }

        return $siblingUrls;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $resourceArguments = $this->findResourceArguments($collectItemData);
        $referenceKey = $this->generateResourceKey($resourceArguments, $this->locale->getLocaleName());

        return [
            UrlCollectorStorageTransfer::REFERENCE_KEY => $referenceKey,
            UrlCollectorStorageTransfer::TYPE => $resourceArguments[self::RESOURCE_TYPE],
            UrlCollectorStorageTransfer::LOCALE_URLS => $collectItemData[UrlCollectorStorageTransfer::LOCALE_URLS],
        ];
    }

    /**
     * @param mixed $data
     * @param string $localeName
     * @param array $collectedItemData
     *
     * @return string
     */
    protected function collectKey($data, $localeName, array $collectedItemData)
    {
        return $this->generateKey($collectedItemData['url'], $localeName);
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return UrlConfig::RESOURCE_TYPE_URL;
    }

    /**
     * @param array $data
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    protected function findResourceArguments(array $data)
    {
        foreach ($data as $columnName => $value) {
            if (!$this->isFkResourceUrl($columnName, $value)) {
                continue;
            }

            $resourceType = str_replace(self::FK_RESOURCE_, '', $columnName);

            return [
                self::RESOURCE_TYPE => $resourceType,
                self::RESOURCE_VALUE => $value,
            ];
        }

        throw new RuntimeException('Invalid array, no resource argument could be extracted.');
    }

    /**
     * @param string $columnName
     * @param string $value
     *
     * @return bool
     */
    protected function isFkResourceUrl($columnName, $value)
    {
        return $value !== null && strpos($columnName, self::FK_RESOURCE_) === 0;
    }

    /**
     * @param array $data
     * @param string $localeName
     *
     * @return string
     */
    protected function generateResourceKey($data, $localeName)
    {
        $keyParts = [
            $this->getCurrentStore()->getName(),
            $localeName,
            'resource',
            $data[self::RESOURCE_TYPE] . '.' . $data[self::RESOURCE_VALUE],
        ];

        return $this->escapeKey(implode(
            $this->keySeparator,
            $keyParts
        ));
    }

    /**
     * @param mixed $data
     * @param string $localeName
     *
     * @return array
     */
    protected function getKeyParts($data, $localeName)
    {
        return [
            $this->getCurrentStore()->getName(),
            $localeName,
            $this->buildKey($data),
        ];
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'url';
    }

    /**
     * @param \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface $batchCollection
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $batchResult
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $storeReader
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function exportDataToStore(
        CountableIteratorInterface $batchCollection,
        TouchUpdaterInterface $touchUpdater,
        BatchResultInterface $batchResult,
        ReaderInterface $storeReader,
        WriterInterface $storeWriter,
        LocaleTransfer $locale,
        OutputInterface $output
    ) {
        if ($batchCollection->count() === 0) {
            return;
        }

        $output->write(PHP_EOL);
        $progressBar = $this->startProgressBar($batchCollection, $batchResult, $output);

        foreach ($batchCollection as $batch) {
            $progressBar->advance(count($batch));
            $this->processUrlKeys($batch, $storeReader, $storeWriter, $locale->getLocaleName());
        }

        $progressBar->finish();

        parent::exportDataToStore(
            $batchCollection,
            $touchUpdater,
            $batchResult,
            $storeReader,
            $storeWriter,
            $locale,
            $output
        );
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     * @param string $itemType
     *
     * @return int
     */
    public function deleteDataFromStore(
        TouchUpdaterInterface $touchUpdater,
        WriterInterface $storeWriter,
        $itemType
    ) {
        $touchCollection = $this->getTouchCollectionToDelete($itemType);
        $keysToDelete = [];

        foreach ($touchCollection as $touchEntry) {
            $touchId = $touchEntry[CollectorConfig::COLLECTOR_TOUCH_ID];
            $touchKey = $touchEntry[CollectorConfig::COLLECTOR_STORAGE_KEY];
            $url = strstr($touchKey, "/");
            $urlKeyPointer = str_replace($url, $touchId, $touchKey);
            $keysToDelete[$urlKeyPointer] = true;
        }

        if ($keysToDelete) {
            $storeWriter->delete($keysToDelete);
        }

        return parent::deleteDataFromStore($touchUpdater, $storeWriter, $itemType);
    }

    /**
     * @param array $data
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $storeReader
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     * @param string $localeName
     *
     * @return void
     */
    protected function processUrlKeys(
        array $data,
        ReaderInterface $storeReader,
        WriterInterface $storeWriter,
        $localeName
    ) {
        foreach ($data as $collectedItemData) {
            $urlTouchKey = $this->collectKey(
                $collectedItemData[CollectorConfig::COLLECTOR_RESOURCE_ID],
                $localeName,
                $collectedItemData
            );

            $url = $collectedItemData[UrlConfig::RESOURCE_TYPE_URL];
            $touchId = $collectedItemData[CollectorConfig::COLLECTOR_TOUCH_ID];
            $urlKeyPointer = str_replace($url, $touchId, $urlTouchKey);

            $this->removeKeyUsingPointerFromStore($storeReader, $storeWriter, $urlKeyPointer);

            $this->writeTouchKeyPointerInStore($urlKeyPointer, $urlTouchKey, $storeWriter);
        }
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $storeReader
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     * @param string $touchKeyPointer
     *
     * @return void
     */
    protected function removeKeyUsingPointerFromStore(
        ReaderInterface $storeReader,
        WriterInterface $storeWriter,
        $touchKeyPointer
    ) {
        /** @var string[] $oldUrl */
        $oldUrl = $storeReader->read($touchKeyPointer);

        if (isset($oldUrl[CollectorConfig::COLLECTOR_STORAGE_KEY])
            && !empty($oldUrl[CollectorConfig::COLLECTOR_STORAGE_KEY])) {
            $storeWriter->delete([
                $oldUrl[CollectorConfig::COLLECTOR_STORAGE_KEY] => true,
            ]);
        }
    }

    /**
     * @param string $touchKeyPointer
     * @param string $touchKey
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     *
     * @return void
     */
    protected function writeTouchKeyPointerInStore($touchKeyPointer, $touchKey, WriterInterface $storeWriter)
    {
        $dataToWrite = [
            $touchKeyPointer => [
                CollectorConfig::COLLECTOR_STORAGE_KEY => $touchKey,
            ],
        ];

        $storeWriter->write($dataToWrite);
    }
}
