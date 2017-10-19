<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemAws3v3FileSystem\Model\Builder\Filesystem;

use Generated\Shared\Transfer\FlysystemConfigAws3v3Transfer;
use Spryker\Service\FlysystemAws3v3FileSystem\Model\Builder\Adapter\Aws3v3AdapterBuilder;

class Aws3v3FilesystemBuilder extends AbstractFilesystemBuilder
{
    /**
     * @return \Generated\Shared\Transfer\FlysystemConfigAws3v3Transfer
     */
    protected function buildAdapterConfig()
    {
        $configTransfer = new FlysystemConfigAws3v3Transfer();
        $configTransfer->fromArray($this->config->getAdapterConfig(), true);

        return $configTransfer;
    }

    /**
     * @return void
     */
    protected function assertAdapterConfig()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        $adapterConfigTransfer->requireRoot();
        $adapterConfigTransfer->requirePath();
        $adapterConfigTransfer->requireKey();
        $adapterConfigTransfer->requireSecret();
        $adapterConfigTransfer->requireBucket();
        $adapterConfigTransfer->requireVersion();
        $adapterConfigTransfer->requireRegion();
    }

    /**
     * @return \Spryker\Service\FlysystemAws3v3FileSystem\Model\Builder\Adapter\AdapterBuilderInterface
     */
    protected function createAdapterBuilder()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        return new Aws3v3AdapterBuilder($adapterConfigTransfer);
    }
}
