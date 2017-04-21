<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Builder\Storage;

use Generated\Shared\Transfer\FileSystemStorageConfigAwsTransfer;
use Spryker\Service\FileSystem\Model\Builder\Storage\FileSystem\AwsS3FileSystemBuilder;

class AwsS3StorageBuilder extends AbstractStorageBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FileSystemStorageConfigAwsTransfer
     */
    protected function buildAdapterConfig()
    {
        $configTransfer = new FileSystemStorageConfigAwsTransfer();
        $configTransfer->fromArray($this->config->getData(), true);

        return $configTransfer;
    }

    /**
     * @return void
     */
    protected function validateConfig()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        $adapterConfigTransfer->requireKey();
        $adapterConfigTransfer->requireSecret();
        $adapterConfigTransfer->requireBucket();
        $adapterConfigTransfer->requireVersion();
        $adapterConfigTransfer->requireRegion();
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\Builder\FileSystemStorageBuilderInterface
     */
    protected function createFileSystemBuilder()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        return new AwsS3FileSystemBuilder($this->config, $adapterConfigTransfer);
    }

}
