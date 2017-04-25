<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder;

use Generated\Shared\Transfer\FlysystemConfigTransfer;

abstract class AbstractBuilder implements FilesystemBuilderInterface
{

    /**
     * @var \Generated\Shared\Transfer\FlysystemConfigTransfer
     */
    protected $config;

    /**
     * @var \Spryker\Service\Flysystem\Model\Builder\FilesystemBuilderInterface
     */
    protected $builder;

    /**
     * @throws \Spryker\Service\Flysystem\Exception\InvalidConfigurationException
     *
     * @return void
     */
    abstract protected function validateConfig();

    /**
     * @return \Spryker\Service\Flysystem\Model\Builder\FilesystemBuilderInterface
     */
    abstract protected function createFileSystemBuilder();

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     */
    public function __construct(FlysystemConfigTransfer $configTransfer)
    {
        $this->config = $configTransfer;
    }

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function build()
    {
        $this->validateMandatoryConfigFields();
        $this->validateConfig();

        $filesystemBuilder = $this->createFileSystemBuilder();

        return $filesystemBuilder->build();
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
