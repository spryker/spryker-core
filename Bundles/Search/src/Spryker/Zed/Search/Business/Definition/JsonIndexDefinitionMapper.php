<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Definition;

use Generated\Shared\Transfer\IndexDefinitionFileTransfer;
use Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeInterface;
use Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface;
use Symfony\Component\Finder\SplFileInfo;

class JsonIndexDefinitionMapper implements IndexDefinitionMapperInterface
{
    /**
     * @var \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        SearchToUtilEncodingInterface $utilEncodingService,
        SearchToStoreFacadeInterface $storeFacade
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo[] $splFiles
     *
     * @return \Generated\Shared\Transfer\IndexDefinitionFileTransfer[]
     */
    public function mapSplFilesToIndexDefinitionFileTransfers(array $splFiles): array
    {
        $indexDefinitionFileTransfers = [];
        $storePrefixes = $this->getStorePrefixes($this->storeFacade->getAllStores());

        foreach ($splFiles as $splFileInfo) {
            $indexDefinitionFileTransfers[] = $this->mapSplFileInfoToIndexDefinitionFileTransfer(
                $splFileInfo,
                new IndexDefinitionFileTransfer(),
                $storePrefixes
            );
        }

        return $indexDefinitionFileTransfers;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     * @param \Generated\Shared\Transfer\IndexDefinitionFileTransfer $indexDefinitionFileTransfer
     * @param string[] $storePrefixes
     *
     * @return \Generated\Shared\Transfer\IndexDefinitionFileTransfer
     */
    protected function mapSplFileInfoToIndexDefinitionFileTransfer(
        SplFileInfo $splFileInfo,
        IndexDefinitionFileTransfer $indexDefinitionFileTransfer,
        array $storePrefixes
    ): IndexDefinitionFileTransfer {
        $decodedContent = $this->utilEncodingService->decodeJson($splFileInfo->getContents(), true);
        $storePrefix = $this->getFileNameStorePrefix($splFileInfo->getFilename(), $storePrefixes);

        return $indexDefinitionFileTransfer
            ->setFileName($splFileInfo->getFilename())
            ->setContent($decodedContent)
            ->setRealPath($splFileInfo->getRealPath())
            ->setStorePrefix($storePrefix);
    }

    /**
     * @param string $fileName
     * @param string[] $storePrefixes
     *
     * @return string|null
     */
    protected function getFileNameStorePrefix(string $fileName, array $storePrefixes): ?string
    {
        foreach ($storePrefixes as $storePrefix) {
            if (strpos($fileName, $storePrefix) === 0) {
                return $storePrefix;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return string[]
     */
    protected function getStorePrefixes(array $storeTransfers): array
    {
        $storePrefixes = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storePrefixes[] = mb_strtolower($storeTransfer->getName()) . '_';
        }

        return $storePrefixes;
    }
}
