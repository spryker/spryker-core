<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemFtpFileSystem\Model\Builder\Adapter;

use Generated\Shared\Transfer\FlysystemConfigFtpTransfer;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;

class FtpAdapterBuilder implements AdapterBuilderInterface
{
    /**
     * @var \League\Flysystem\Ftp\FtpAdapter
     */
    protected $adapter;

    /**
     * @var \Generated\Shared\Transfer\FlysystemConfigFtpTransfer
     */
    protected $adapterConfig;

    /**
     * @var \League\Flysystem\Ftp\FtpConnectionOptions
     */
    protected $connectionOptions;

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigFtpTransfer $adapterConfig
     */
    public function __construct(FlysystemConfigFtpTransfer $adapterConfig)
    {
        $this->adapterConfig = $adapterConfig;
    }

    /**
     * @return \League\Flysystem\FilesystemAdapter
     */
    public function build()
    {
        $this
            ->buildFtpConnectionOptions()
            ->buildAdapter();

        return $this->adapter;
    }

    /**
     * @return $this
     */
    protected function buildFtpConnectionOptions()
    {
        $this->connectionOptions = new FtpConnectionOptions(
            $this->adapterConfig->getHostOrFail(),
            '',
            $this->adapterConfig->getUsernameOrFail(),
            $this->adapterConfig->getPasswordOrFail()
        );

        return $this;
    }

    /**
     * @return $this
     */
    protected function buildAdapter()
    {
        $this->adapter = new FtpAdapter($this->connectionOptions);

        return $this;
    }
}
