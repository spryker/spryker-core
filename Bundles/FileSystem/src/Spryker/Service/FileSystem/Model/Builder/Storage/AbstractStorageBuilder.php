<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Builder\Storage;

use Generated\Shared\Transfer\FileSystemStorageConfigTransfer;
use Spryker\Service\FileSystem\Model\Builder\FileSystemStorageBuilderInterface;

abstract class AbstractStorageBuilder implements FileSystemStorageBuilderInterface
{

    /**
     * @var \Generated\Shared\Transfer\FileSystemStorageConfigTransfer
     */
    protected $config;

    /**
     * @var \Spryker\Service\FileSystem\Model\Builder\FileSystemStorageBuilderInterface
     */
    protected $builder;

    /**
     * @throws \Spryker\Service\FileSystem\Exception\FileSystemInvalidConfigurationException
     *
     * @return void
     */
    abstract protected function validateConfig();

    /**
     * @return \Spryker\Service\FileSystem\Model\Builder\FileSystemStorageBuilderInterface
     */
    abstract protected function createFileSystemBuilder();

    /**
     * @param \Generated\Shared\Transfer\FileSystemStorageConfigTransfer $configTransfer
     */
    public function __construct(FileSystemStorageConfigTransfer $configTransfer)
    {
        $this->config = $configTransfer;
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\FileSystemStorageInterface
     */
    public function build()
    {
        $this->validateMandatoryConfigFields();
        $this->validateConfig();

        $fileSystemBuilder = $this->createFileSystemBuilder();

        return $fileSystemBuilder->build();
    }

    /**
     * @return void
     */
    protected function validateMandatoryConfigFields()
    {
        $this->config->requireName();
        $this->config->requireType();
        $this->config->requireData();
    }

}
