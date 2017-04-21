<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Builder\FileSystem;

use Generated\Shared\Transfer\FileSystemStorageConfigAwsTransfer;

class AwsS3FileSystemBuilder extends AbstractFileSystemBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FileSystemStorageConfigAwsTransfer
     */
    protected function buildStorageConfig()
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
        $storageConfigTransfer = $this->buildStorageConfig();

        $storageConfigTransfer->requireKey();
        $storageConfigTransfer->requireSecret();
        $storageConfigTransfer->requireBucket();
        $storageConfigTransfer->requireVersion();
        $storageConfigTransfer->requireRegion();
    }

}
