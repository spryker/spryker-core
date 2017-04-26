<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder\Filesystem;

use Generated\Shared\Transfer\FlysystemConfigAwsTransfer;
use Spryker\Service\Flysystem\Model\Builder\Adapter\AwsS3AdapterBuilder;

class AwsS3FilesystemBuilder extends AbstractFilesystemBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FlysystemConfigAwsTransfer
     */
    protected function buildAdapterConfig()
    {
        $configTransfer = new FlysystemConfigAwsTransfer();
        $configTransfer->fromArray($this->config->getAdapterConfig(), true);

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
     * @return \Spryker\Service\Flysystem\Model\Builder\Adapter\AdapterBuilderInterface
     */
    protected function createAdapterBuilder()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        return new AwsS3AdapterBuilder($this->config, $adapterConfigTransfer);
    }

}
