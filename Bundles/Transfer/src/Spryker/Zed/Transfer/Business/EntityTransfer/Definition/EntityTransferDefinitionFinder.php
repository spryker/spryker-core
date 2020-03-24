<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\EntityTransfer\Definition;

use Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface;
use Spryker\Zed\Transfer\Dependency\Service\TransferToUtilGlobServiceInterface;
use Spryker\Zed\Transfer\TransferConfig;
use Symfony\Component\Finder\Finder;

class EntityTransferDefinitionFinder implements FinderInterface
{
    /**
     * @var \Spryker\Zed\Transfer\TransferConfig|null
     */
    protected $transferConfig;

    /**
     * @var \Spryker\Zed\Transfer\Dependency\Service\TransferToUtilGlobServiceInterface|null
     */
    protected $globService;

    /**
     * @param \Spryker\Zed\Transfer\TransferConfig $transferConfig
     * @param \Spryker\Zed\Transfer\Dependency\Service\TransferToUtilGlobServiceInterface $globService
     */
    public function __construct(TransferConfig $transferConfig, TransferToUtilGlobServiceInterface $globService)
    {
        $this->transferConfig = $transferConfig;
        $this->globService = $globService;
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

        $finder->in($existingSourceDirectories)->name($this->transferConfig->getEntityFileNamePattern())->depth('< 1');

        return iterator_to_array($finder->getIterator());
    }

    /**
     * @return string[]
     */
    protected function getExistingSourceDirectories()
    {
        $existingDirectories = [];

        foreach ($this->transferConfig->getEntitiesSourceDirectories() as $sourceDirectory) {
            $existingDirectories = array_merge($existingDirectories, $this->glob($sourceDirectory));
        }

        return $existingDirectories;
    }

    /**
     * @param string $sourceDirectory
     *
     * @return array
     */
    protected function glob(string $sourceDirectory): array
    {
        return $this->globService->glob($sourceDirectory, GLOB_ONLYDIR | GLOB_NOSORT);
    }
}
