<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\EntityTransfer\Definition;

use Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface;
use Spryker\Zed\Transfer\Dependency\Facade\TransferToPropelFacadeInterface;
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
     * @var \Spryker\Zed\Transfer\Dependency\Facade\TransferToPropelFacadeInterface
     */
    protected $propelFacade;

    /**
     * @param \Spryker\Zed\Transfer\TransferConfig $transferConfig
     * @param \Spryker\Zed\Transfer\Dependency\Service\TransferToUtilGlobServiceInterface $globService
     * @param \Spryker\Zed\Transfer\Dependency\Facade\TransferToPropelFacadeInterface $propelFacade
     */
    public function __construct(
        TransferConfig $transferConfig,
        TransferToUtilGlobServiceInterface $globService,
        TransferToPropelFacadeInterface $propelFacade
    ) {
        $this->transferConfig = $transferConfig;
        $this->globService = $globService;
        $this->propelFacade = $propelFacade;
    }

    /**
     * @return array<\Symfony\Component\Finder\SplFileInfo>
     */
    public function getXmlTransferDefinitionFiles()
    {
        $finder = new Finder();

        $propelSchemaPath = $this->getPropelSchemaPath();

        if (!is_dir($propelSchemaPath)) {
            return [];
        }

        $finder->in($propelSchemaPath)->name($this->transferConfig->getEntityFileNamePattern())->depth('< 1');

        return iterator_to_array($finder->getIterator());
    }

    /**
     * @return string
     */
    protected function getPropelSchemaPath(): string
    {
        return $this->propelFacade->getSchemaDirectory();
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
