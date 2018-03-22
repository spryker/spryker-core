<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemLocalFileSystem\Model\Builder\Adapter;

use Generated\Shared\Transfer\FlysystemConfigLocalTransfer;
use League\Flysystem\Adapter\Local as LocalAdapter;

class LocalAdapterBuilder implements AdapterBuilderInterface
{
    /**
     * @var \League\Flysystem\Adapter\Local
     */
    protected $adapter;

    /**
     * @var \Generated\Shared\Transfer\FlysystemConfigLocalTransfer
     */
    protected $adapterConfig;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigLocalTransfer $adapterConfig
     */
    public function __construct(FlysystemConfigLocalTransfer $adapterConfig)
    {
        $this->adapterConfig = $adapterConfig;
    }

    /**
     * @return \League\Flysystem\AdapterInterface
     */
    public function build()
    {
        $this
            ->buildPath()
            ->buildAdapter();

        return $this->adapter;
    }

    /**
     * @return $this
     */
    protected function buildPath()
    {
        $this->path = sprintf(
            '%s%s%s',
            $this->adapterConfig->getRoot(),
            DIRECTORY_SEPARATOR,
            $this->adapterConfig->getPath()
        );

        return $this;
    }

    /**
     * @return $this
     */
    protected function buildAdapter()
    {
        $this->adapter = new LocalAdapter($this->path, LOCK_EX, LocalAdapter::DISALLOW_LINKS);

        return $this;
    }
}
