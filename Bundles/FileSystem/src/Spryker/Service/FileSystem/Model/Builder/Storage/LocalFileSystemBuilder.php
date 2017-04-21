<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Builder\FileSystem;

use Generated\Shared\Transfer\FileSystemStorageConfigLocalTransfer;
use Spryker\Service\FileSystem\Model\Builder\Storage\LocalStorageBuilder;

class LocalFileSystemBuilder extends AbstractFileSystemBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FileSystemStorageConfigLocalTransfer
     */
    protected function buildStorageConfig()
    {
        $configTransfer = new FileSystemStorageConfigLocalTransfer();
        $configTransfer->fromArray($this->config->getData(), true);

        return $configTransfer;
    }

    /**
     * @return void
     */
    protected function validateConfig()
    {
        $storageConfigTransfer = $this->buildStorageConfig();

        $storageConfigTransfer->requirePath();
        $storageConfigTransfer->requireRoot();
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\Builder\FileSystemStorageBuilderInterface
     */
    protected function buildStorage()
    {
        $storageConfigTransfer = $this->buildStorageConfig();

        return new LocalStorageBuilder($storageConfigTransfer);
    }

}
