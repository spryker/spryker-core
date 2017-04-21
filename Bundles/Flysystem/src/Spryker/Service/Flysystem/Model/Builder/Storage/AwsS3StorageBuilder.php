<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder\Storage;

use Generated\Shared\Transfer\FlysystemStorageConfigAwsTransfer;
use Spryker\Service\Flysystem\Model\Builder\Storage\Flysystem\AwsS3FlysystemBuilder;

class AwsS3StorageBuilder extends AbstractStorageBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FlysystemStorageConfigAwsTransfer
     */
    protected function buildAdapterConfig()
    {
        $configTransfer = new FlysystemStorageConfigAwsTransfer();
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
     * @return \Spryker\Service\Flysystem\Model\Builder\FlysystemStorageBuilderInterface
     */
    protected function createFlysystemBuilder()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        return new AwsS3FlysystemBuilder($this->config, $adapterConfigTransfer);
    }

}
