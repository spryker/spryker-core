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
use Spryker\Zed\Sitemap\Dependency\Facade\SitemapToStoreFacadeInterface;
use Spryker\Zed\Sitemap\Dependency\Service\SitemapToFileSystemServiceInterface;
use Spryker\Zed\Sitemap\SitemapConfig;
use Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapDataProviderPluginInterface;

class SitemapGenerator implements SitemapGeneratorInterface
{
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
     * @param array<\Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapDataProviderPluginInterface> $sitemapDataProviderPlugins
     */
    public function __construct(
        protected SitemapToStoreFacadeInterface $storeFacade,
        protected XmlGeneratorInterface $xmlGenerator,
        protected SitemapToFileSystemServiceInterface $fileSystemService,
        protected SitemapConfig $sitemapConfig,
        protected array $sitemapDataProviderPlugins
    ) {
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

            $sitemapXmlContentIndexedByFileName = [];

            foreach ($this->sitemapDataProviderPlugins as $sitemapDataProviderPlugin) {
                $sitemapXmlContentIndexedByFileName += $this->getSitemapXmlContentIndexedByFileName($sitemapDataProviderPlugin, $storeName, $yvesHost);
            }

            $sitemapXmlContentIndexedByFileName[SitemapConstants::SITEMAP_INDEX_FILE_NAME] = $this->xmlGenerator->generateSitemapIndexXmlContent(
                array_keys($sitemapXmlContentIndexedByFileName),
                $yvesHost,
            );

            $this->saveSitemapFiles($sitemapXmlContentIndexedByFileName, $storeName);
        }
    }

    /**
     * @param \Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapDataProviderPluginInterface $sitemapDataProviderPlugin
     * @param string $storeName
     * @param string $yvesHost
     *
     * @return array<string>
     */
    protected function getSitemapXmlContentIndexedByFileName(
        SitemapDataProviderPluginInterface $sitemapDataProviderPlugin,
        string $storeName,
        string $yvesHost
    ): array {
        $entityType = $sitemapDataProviderPlugin->getEntityType();
        $sitemapUrlTransfers = $sitemapDataProviderPlugin->getSitemapUrls($storeName);
        $sitemapUrlTransfersGroupedByIdEntity = $this->groupSitemapUrlTransfersByIdEntity($sitemapUrlTransfers);
        $sitemapPageNumber = 1;
        $sitemapXmlContentIndexedByFileName = [];
        $chunkSize = max(1, (int)$this->sitemapConfig->getSitemapUrlLimit());

        foreach (array_chunk($sitemapUrlTransfers, $chunkSize) as $sitemapUrlTransfersChunk) {
            $fileName = $this->generateSitemapFileName($entityType, $sitemapPageNumber++);
            $sitemapXmlContentIndexedByFileName[$fileName] = $this->xmlGenerator->generateSitemapXmlContent(
                $sitemapUrlTransfersChunk,
                $sitemapUrlTransfersGroupedByIdEntity,
                $yvesHost,
            );
        }

        return $sitemapXmlContentIndexedByFileName;
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
     * @param array<string, string> $sitemapXmlContentIndexedByFileName
     * @param string $storeName
     *
     * @return void
     */
    protected function saveSitemapFiles(array $sitemapXmlContentIndexedByFileName, string $storeName): void
    {
        foreach ($sitemapXmlContentIndexedByFileName as $fileName => $content) {
            $this->writeFile($fileName, $content, $storeName);
        }

        foreach ($this->getExistingSitemapFiles($storeName) as $fileSystemResourceTransfer) {
            $existingFileName = ltrim($fileSystemResourceTransfer->getPathOrFail(), $storeName . DIRECTORY_SEPARATOR);
            if (isset($sitemapXmlContentIndexedByFileName[$existingFileName])) {
                continue;
            }

            $this->deleteFile($fileSystemResourceTransfer->getPathOrFail());
        }
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
}
