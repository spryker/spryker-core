<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemFtpFileSystem\Model\Builder\Filesystem;

use Generated\Shared\Transfer\FlysystemConfigFtpTransfer;
use Spryker\Service\FlysystemFtpFileSystem\Model\Builder\Adapter\FtpAdapterBuilder;

class FtpFilesystemBuilder extends AbstractFilesystemBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FlysystemConfigFtpTransfer
     */
    protected function buildAdapterConfig()
    {
        $configTransfer = new FlysystemConfigFtpTransfer();
        $configTransfer->fromArray($this->config->getAdapterConfig(), true);

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
     * @return \Spryker\Service\FlysystemFtpFileSystem\Model\Builder\Adapter\AdapterBuilderInterface
     */
    protected function createAdapterBuilder()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        return new FtpAdapterBuilder($adapterConfigTransfer);
    }

}
