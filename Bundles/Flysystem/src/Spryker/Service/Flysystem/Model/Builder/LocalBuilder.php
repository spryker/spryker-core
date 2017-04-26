<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder;

use Generated\Shared\Transfer\FlysystemConfigLocalTransfer;
use Spryker\Service\Flysystem\Model\Builder\Filesystem\LocalFilesystemBuilder;

class LocalBuilder extends AbstractBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FlysystemConfigLocalTransfer
     */
    protected function buildAdapterConfig()
    {
        $configTransfer = new FlysystemConfigLocalTransfer();
        $configTransfer->fromArray($this->config->getData(), true);

        return $configTransfer;
    }

    /**
     * @return void
     */
    protected function assertAdapterConfig()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        $adapterConfigTransfer->requirePath();
        $adapterConfigTransfer->requireRoot();
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\Builder\FilesystemBuilderInterface
     */
    protected function createFileSystemBuilder()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        return new LocalFilesystemBuilder($this->config, $adapterConfigTransfer, $this->pluginProvider);
    }

}
