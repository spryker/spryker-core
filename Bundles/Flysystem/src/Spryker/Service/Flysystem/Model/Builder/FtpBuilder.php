<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder;

use Generated\Shared\Transfer\FlysystemConfigFtpTransfer;
use Spryker\Service\Flysystem\Model\Builder\Filesystem\FtpFilesystemBuilder;

class FtpBuilder extends AbstractBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FlysystemConfigFtpTransfer
     */
    protected function buildAdapterConfig()
    {
        $configTransfer = new FlysystemConfigFtpTransfer();
        $configTransfer->fromArray($this->config->getData(), true);

        return $configTransfer;
    }

    /**
     * @return void
     */
    protected function assertAdapterConfig()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        $adapterConfigTransfer->requireHost();
        $adapterConfigTransfer->requireUsername();
        $adapterConfigTransfer->requirePassword();
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\Builder\FilesystemBuilderInterface
     */
    protected function createFileSystemBuilder()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        return new FtpFilesystemBuilder($this->config, $adapterConfigTransfer, $this->pluginProvider);
    }

}
