<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder;

use Generated\Shared\Transfer\FlysystemConfigAwsTransfer;
use Spryker\Service\Flysystem\Model\Builder\Filesystem\AwsS3FilesystemBuilder;

class AwsS3Builder extends AbstractBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FlysystemConfigAwsTransfer
     */
    protected function buildAdapterConfig()
    {
        $configTransfer = new FlysystemConfigAwsTransfer();
        $configTransfer->fromArray($this->config->getData(), true);

        return $configTransfer;
    }

    /**
     * @return void
     */
    protected function assertAdapterConfig()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        $adapterConfigTransfer->requireKey();
        $adapterConfigTransfer->requireSecret();
        $adapterConfigTransfer->requireBucket();
        $adapterConfigTransfer->requireVersion();
        $adapterConfigTransfer->requireRegion();
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\Builder\FilesystemBuilderInterface
     */
    protected function createFileSystemBuilder()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        return new AwsS3FilesystemBuilder($this->config, $adapterConfigTransfer, $this->pluginProvider);
    }

}
