<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sitemap\Business\Generator;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemDeleteTransfer;
use Generated\Shared\Transfer\FileSystemListTransfer;
use Spryker\Shared\Sitemap\SitemapConstants;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;
use Spryker\Zed\Sitemap\Dependency\Facade\SitemapToStoreFacadeInterface;
use Spryker\Zed\Sitemap\Dependency\Service\SitemapToFileSystemServiceInterface;
use Spryker\Zed\Sitemap\SitemapConfig;
use Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapGeneratorDataProviderPluginInterface;

class SitemapGenerator implements SitemapGeneratorInterface
{
    use InstancePoolingTrait;

    /**
     * @var int
     */
    protected const PORT_HTTPS = 443;

    /**
     * @var string
     */
    protected const SITE_MAP_FILE_NAME_PLACEHOLDER = '%s_%s_%s%s';

    /**
     * @var string
     */
    protected const DOT_XML_EXTENSION = '.xml';

    /**
     * @param \Spryker\Zed\Sitemap\Dependency\Facade\SitemapToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Sitemap\Business\Generator\XmlGeneratorInterface $xmlGenerator
     * @param \Spryker\Zed\Sitemap\Dependency\Service\SitemapToFileSystemServiceInterface $fileSystemService
     * @param \Spryker\Zed\Sitemap\SitemapConfig $sitemapConfig
     * @param array<\Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapGeneratorDataProviderPluginInterface> $sitemapGeneratorDataProviderPlugins
     */
    public function __construct(
        protected SitemapToStoreFacadeInterface $storeFacade,
        protected XmlGeneratorInterface $xmlGenerator,
        protected SitemapToFileSystemServiceInterface $fileSystemService,
        protected SitemapConfig $sitemapConfig,
        protected array $sitemapGeneratorDataProviderPlugins
    ) {
        $this->disableInstancePooling();
    }

    /**
     * @return void
     */
    public function generateSitemapFiles(): void
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($storeTransfers as $storeTransfer) {
            $storeName = $storeTransfer->getNameOrFail();
            $yvesHost = $this->getBaseUrl($storeName);
            $fileNames = [];

            foreach ($this->sitemapGeneratorDataProviderPlugins as $plugin) {
                $fileNames = array_merge(
                    $fileNames,
                    $this->getPaginatedSitemapXmlContentIndexedByFileName($plugin, $storeName, $yvesHost),
                );
            }

            $indexContent = $this->xmlGenerator->generateSitemapIndexXmlContent(
                $fileNames,
                $yvesHost,
            );

            $this->writeFile(SitemapConstants::SITEMAP_INDEX_FILE_NAME, $indexContent, $storeName);
            $fileNames[] = SitemapConstants::SITEMAP_INDEX_FILE_NAME;

            $this->removeOutdatedSitemapFiles($fileNames, $storeName);
        }
    }

    /**
     * @param \Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapGeneratorDataProviderPluginInterface $sitemapGeneratorDataProviderPlugin
     * @param string $storeName
     * @param string $yvesHost
     *
     * @return array<string>
     */
    protected function getPaginatedSitemapXmlContentIndexedByFileName(
        SitemapGeneratorDataProviderPluginInterface $sitemapGeneratorDataProviderPlugin,
        string $storeName,
        string $yvesHost
    ): array {
        $entityType = $sitemapGeneratorDataProviderPlugin->getEntityType();
        $sitemapPageNumber = 1;
        $chunkSize = max(1, (int)$this->sitemapConfig->getSitemapUrlLimit());
        $generatorEntityLimit = max(1, (int)$this->sitemapConfig->getGeneratorEnitityLimit());
        $fileNames = [];
        $sitemapUrlTransfers = [];

        foreach ($sitemapGeneratorDataProviderPlugin->getSitemapUrls($storeName, $generatorEntityLimit) as $sitemapUrlPaginatedTransfers) {
            $sitemapUrlTransfers = array_merge($sitemapUrlTransfers, $sitemapUrlPaginatedTransfers);

            while (count($sitemapUrlTransfers) >= $chunkSize) {
                $sitemapUrlChunk = array_slice($sitemapUrlTransfers, 0, $chunkSize);
                $sitemapUrlTransfers = array_slice($sitemapUrlTransfers, $chunkSize);

                $fileNames[] = $this->writeSitemapChunk($entityType, $sitemapPageNumber++, $sitemapUrlChunk, $yvesHost, $storeName);
            }
        }

        if ($sitemapUrlTransfers) {
            $fileNames[] = $this->writeSitemapChunk($entityType, $sitemapPageNumber++, $sitemapUrlTransfers, $yvesHost, $storeName);
        }

        return $fileNames;
    }

    /**
     * @param string $entityType
     * @param int $pageNumber
     * @param array<\Generated\Shared\Transfer\SitemapUrlTransfer> $sitemapUrlTransfers
     * @param string $yvesHost
     * @param string $storeName
     *
     * @return string
     */
    protected function writeSitemapChunk(
        string $entityType,
        int $pageNumber,
        array $sitemapUrlTransfers,
        string $yvesHost,
        string $storeName
    ): string {
        $fileName = $this->generateSitemapFileName($entityType, $pageNumber);

        $content = $this->xmlGenerator->generateSitemapXmlContent(
            $sitemapUrlTransfers,
            $this->groupSitemapUrlTransfersByIdEntity($sitemapUrlTransfers),
            $yvesHost,
        );

        $this->writeFile($fileName, $content, $storeName);

        unset($content);
        gc_collect_cycles();

        return $fileName;
    }

    /**
     * @param string $entityType
     * @param int $pageNumber
     *
     * @return string
     */
    protected function generateSitemapFileName(string $entityType, int $pageNumber): string
    {
        return sprintf(
            static::SITE_MAP_FILE_NAME_PLACEHOLDER,
            SitemapConstants::SITEMAP_FILE_NAME_PREFIX,
            $entityType,
            $pageNumber,
            static::DOT_XML_EXTENSION,
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\SitemapUrlTransfer> $sitemapUrlTransfers
     *
     * @return array<array<int, \Generated\Shared\Transfer\SitemapUrlTransfer>>
     */
    protected function groupSitemapUrlTransfersByIdEntity(array $sitemapUrlTransfers): array
    {
        $sitemapUrlTransfersGroupedByIdEntity = [];

        foreach ($sitemapUrlTransfers as $sitemapUrlTransfer) {
            $sitemapUrlTransfersGroupedByIdEntity[$sitemapUrlTransfer->getIdEntity()][] = $sitemapUrlTransfer;
        }

        return $sitemapUrlTransfersGroupedByIdEntity;
    }

    /**
     * @param string $fileName
     * @param string $content
     * @param string $storeName
     *
     * @return void
     */
    protected function writeFile(string $fileName, string $content, string $storeName): void
    {
        $fileSystemContentTransfer = (new FileSystemContentTransfer())
            ->setFileSystemName(SitemapConstants::FILESYSTEM_NAME)
            ->setPath($this->sitemapConfig->getFilePath($storeName, $fileName))
            ->setContent($content);

        $this->fileSystemService->write($fileSystemContentTransfer);
    }

    /**
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\FileSystemResourceTransfer>
     */
    protected function getExistingSitemapFiles(string $storeName): array
    {
        $fileSystemListTransfer = (new FileSystemListTransfer())
            ->setFileSystemName(SitemapConstants::FILESYSTEM_NAME)
            ->setPath($storeName)
            ->setRecursive(false);

        return $this->fileSystemService->listContents($fileSystemListTransfer);
    }

    /**
     * @param string $path
     *
     * @return void
     */
    protected function deleteFile(string $path): void
    {
        $fileSystemDeleteTransfer = (new FileSystemDeleteTransfer())
            ->setFileSystemName(SitemapConstants::FILESYSTEM_NAME)
            ->setPath($path);

        $this->fileSystemService->delete($fileSystemDeleteTransfer);
    }

    /**
     * @param string $storeName
     *
     * @return string
     */
    protected function getBaseUrl(string $storeName): string
    {
        if ($this->storeFacade->isDynamicStoreEnabled() === false) {
            $yvesHost = $this->sitemapConfig->getStoreToYvesHostMapping()[$storeName];

            return $this->generateBaseUrl($yvesHost);
        }

        $yvesHost = $this->sitemapConfig->getRegionToYvesHostMapping()[$this->sitemapConfig->getCurrentRegion()];

        return $this->generateBaseUrl($yvesHost);
    }

    /**
     * @param string $yvesHost
     *
     * @return string
     */
    protected function generateBaseUrl(string $yvesHost): string
    {
        return sprintf(
            '%s://%s',
            $this->sitemapConfig->getBaseUrlYvesPort() === static::PORT_HTTPS ? 'https' : 'http',
            $yvesHost,
        );
    }

    /**
     * @param array<string> $validFileNames
     * @param string $storeName
     *
     * @return void
     */
    protected function removeOutdatedSitemapFiles(array $validFileNames, string $storeName): void
    {
        $existingFiles = $this->getExistingSitemapFiles($storeName);

        foreach ($existingFiles as $resourceTransfer) {
            $existingFileName = ltrim($resourceTransfer->getPathOrFail(), $storeName . DIRECTORY_SEPARATOR);
            if (!in_array($existingFileName, $validFileNames, true)) {
                $this->deleteFile($resourceTransfer->getPathOrFail());
            }
        }
    }
}
