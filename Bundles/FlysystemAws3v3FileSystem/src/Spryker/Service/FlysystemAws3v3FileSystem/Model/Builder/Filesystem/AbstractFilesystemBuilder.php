<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemAws3v3FileSystem\Model\Builder\Filesystem;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use League\Flysystem\Filesystem;
use Spryker\Service\FlysystemAws3v3FileSystem\Model\Builder\Adapter\AdapterBuilderInterface;

abstract class AbstractFilesystemBuilder implements FilesystemBuilderInterface
{
    /**
     * @var \Generated\Shared\Transfer\FlysystemConfigTransfer
     */
    protected $config;

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $configTransfer
     */
    public function __construct(FlysystemConfigTransfer $configTransfer)
    {
        $this->config = $configTransfer;
    }

    /**
     * @return void
     */
    abstract protected function assertAdapterConfig(): void;

    /**
     * @return \Spryker\Service\FlysystemAws3v3FileSystem\Model\Builder\Adapter\AdapterBuilderInterface
     */
    abstract protected function createAdapterBuilder(): AdapterBuilderInterface;

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function build(): Filesystem
    {
        $this->assertAdapterConfig();
        $filesystem = $this->buildFilesystem();

        return $filesystem;
    }

    /**
     * @return \League\Flysystem\Filesystem
     */
    protected function buildFilesystem(): Filesystem
    {
        $adapter = $this->createAdapterBuilder()->build();
        $config = $this->config->getFlysystemConfig() ?: [];

        return new Filesystem($adapter, $config);
    }
}
