<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model;

use League\Flysystem\Filesystem;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class FlysystemStorage implements FlysystemStorageInterface
{

    /**
     * @var \Generated\Shared\Transfer\FlysystemStorageConfigTransfer
     */
    protected $storageConfig;

    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $fileSystem;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $storageConfigTransfer
     * @param \League\Flysystem\Filesystem $fileSystem
     */
    public function __construct(AbstractTransfer $storageConfigTransfer, Filesystem $fileSystem)
    {
        $this->storageConfig = $storageConfigTransfer;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function getFlysystem()
    {
        return $this->fileSystem;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->storageConfig
            ->requireName()
            ->getName();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->storageConfig
            ->requireType()
            ->getType();
    }

}
