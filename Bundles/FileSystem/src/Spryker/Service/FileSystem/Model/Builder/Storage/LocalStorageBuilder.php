<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Builder\Storage;

use Generated\Shared\Transfer\FileSystemStorageConfigLocalTransfer;
use Spryker\Service\FileSystem\Model\Builder\Storage\FileSystem\LocalFileSystemBuilder;

class LocalStorageBuilder extends AbstractStorageBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FileSystemStorageConfigLocalTransfer
     */
    protected function buildAdapterConfig()
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
        $adapterConfigTransfer = $this->buildAdapterConfig();

        $adapterConfigTransfer->requirePath();
        $adapterConfigTransfer->requireRoot();
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\Builder\FileSystemStorageBuilderInterface
     */
    protected function createFileSystemBuilder()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        return new LocalFileSystemBuilder($this->config, $adapterConfigTransfer);
    }

}
