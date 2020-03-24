<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Symfony\Component\Finder\Finder;

/**
 * @deprecated Use {@link \Spryker\Zed\Transfer\Business\Transfer\Definition\TransferDefinitionFinder} instead.
 * @deprecated Use {@link \Spryker\Zed\Transfer\Business\EntityTransfer\Definition\EntityTransferDefinitionFinder} instead.
 * @deprecated Use {@link \Spryker\Zed\Transfer\Business\DataBuilder\Definition\DataBuilderDefinitionFinder} instead.
 */
class TransferDefinitionFinder implements FinderInterface
{
    /**
     * @deprecated Will be removed with next major release
     */
    public const KEY_BUNDLE = 'bundle';

    /**
     * @deprecated Will be removed with next major release
     */
    public const KEY_CONTAINING_BUNDLE = 'containing bundle';

    /**
     * @deprecated Will be removed with next major release
     */
    public const KEY_TRANSFER = 'transfer';

    /**
     * @deprecated Will be removed with next major release
     */
    public const TRANSFER_SCHEMA_SUFFIX = '.transfer.xml';

    /**
     * @var array
     */
    protected $sourceDirectories;

    /**
     * @var string
     */
    protected $fileNamePattern;

    /**
     * @param array $sourceDirectories
     * @param string $fileNamePattern
     */
    public function __construct(array $sourceDirectories, $fileNamePattern = '*.transfer.xml')
    {
        $this->sourceDirectories = $sourceDirectories;
        $this->fileNamePattern = $fileNamePattern;
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    public function getXmlTransferDefinitionFiles()
    {
        $finder = new Finder();

        $existingSourceDirectories = $this->getExistingSourceDirectories();
        if (empty($existingSourceDirectories)) {
            return [];
        }

        $finder->in($existingSourceDirectories)->name($this->fileNamePattern)->depth('< 1');

        return iterator_to_array($finder->getIterator());
    }

    /**
     * @return string[]
     */
    protected function getExistingSourceDirectories()
    {
        return array_filter($this->sourceDirectories, function ($directory) {
            return (bool)glob($directory, GLOB_ONLYDIR | GLOB_NOSORT);
        });
    }
}
