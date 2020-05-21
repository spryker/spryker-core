<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Definition;

use Generated\Shared\Transfer\IndexDefinitionFileTransfer;
use Symfony\Component\Finder\Finder;

class JsonIndexDefinitionFinder implements IndexDefinitionFinderInterface
{
    protected const FILE_EXTENSION = '.json';

    /**
     * @var array
     */
    protected $sourceDirectories;

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionMapperInterface
     */
    protected $indexDefinitionMapper;

    /**
     * @param array $sourceDirectories
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionMapperInterface $indexDefinitionMapper
     */
    public function __construct(
        array $sourceDirectories,
        IndexDefinitionMapperInterface $indexDefinitionMapper
    ) {
        $this->sourceDirectories = $sourceDirectories;
        $this->indexDefinitionMapper = $indexDefinitionMapper;
    }

    /**
     * @return \Generated\Shared\Transfer\IndexDefinitionFileTransfer[]
     */
    public function getSortedIndexDefinitionFileTransfers(): array
    {
        $finder = (new Finder())
            ->in($this->sourceDirectories)
            ->name('*' . static::FILE_EXTENSION);

        $jsonFiles = [];
        foreach ($finder as $splFileInfo) {
            $jsonFiles[] = $splFileInfo;
        }

        $indexDefinitionFileTransfers = $this->indexDefinitionMapper->mapSplFilesToIndexDefinitionFileTransfers($jsonFiles);
        usort($indexDefinitionFileTransfers, [$this, 'sortIndexDefinitionFileTransfers']);

        return $indexDefinitionFileTransfers;
    }

    /**
     * Sorts IndexDefinitionFile transfers by store prefixes and then by real path.
     *
     * @param \Generated\Shared\Transfer\IndexDefinitionFileTransfer $firstIndexDefinitionFileTransfer
     * @param \Generated\Shared\Transfer\IndexDefinitionFileTransfer $secondIndexDefinitionFileTransfer
     *
     * @return int
     */
    protected function sortIndexDefinitionFileTransfers(
        IndexDefinitionFileTransfer $firstIndexDefinitionFileTransfer,
        IndexDefinitionFileTransfer $secondIndexDefinitionFileTransfer
    ): int {
        $hasFirstIndexDefinitionFileTransferPrefix = (bool)$firstIndexDefinitionFileTransfer->getStorePrefix();
        $hasSecondIndexDefinitionFileTransferPrefix = (bool)$secondIndexDefinitionFileTransfer->getStorePrefix();

        if ($hasFirstIndexDefinitionFileTransferPrefix !== $hasSecondIndexDefinitionFileTransferPrefix) {
            return (int)($hasFirstIndexDefinitionFileTransferPrefix > $hasSecondIndexDefinitionFileTransferPrefix);
        }

        return strcmp($firstIndexDefinitionFileTransfer->getRealPath(), $secondIndexDefinitionFileTransfer->getRealPath());
    }
}
