<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Storage;

use Generated\Shared\Transfer\FileSystemStorageConfigTransfer;

abstract class AbstractStorageBuilder implements BuilderInterface
{

    /**
     * @var \Generated\Shared\Transfer\FileSystemStorageConfigTransfer
     */
    protected $config;

    /**
     * @return \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface
     */
    abstract protected function buildStorage();

    /**
     * @throws \Spryker\Service\FileSystem\Model\Exception\FileSystemInvalidConfigurationException
     *
     * @return void
     */
    abstract protected function validateStorageConfig();

    /**
     * @param \Generated\Shared\Transfer\FileSystemStorageConfigTransfer $configTransfer
     */
    public function __construct(FileSystemStorageConfigTransfer $configTransfer)
    {
        $this->config = $configTransfer;
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface
     */
    public function build()
    {
        $this->validateMandatoryConfigFields();
        $this->validateStorageConfig();

        return $this->buildStorage();
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
