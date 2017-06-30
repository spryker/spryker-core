<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemLocalFileSystem\Model\Builder\Filesystem;

use Generated\Shared\Transfer\FlysystemConfigLocalTransfer;
use Spryker\Service\FlysystemLocalFileSystem\Model\Builder\Adapter\LocalAdapterBuilder;

class LocalFilesystemBuilder extends AbstractFilesystemBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FlysystemConfigLocalTransfer
     */
    protected function buildAdapterConfig()
    {
        $configTransfer = new FlysystemConfigLocalTransfer();
        $configTransfer->fromArray($this->config->getAdapterConfig(), true);

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
     * @return \Spryker\Service\FlysystemLocalFileSystem\Model\Builder\Adapter\AdapterBuilderInterface
     */
    protected function createAdapterBuilder()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        return new LocalAdapterBuilder($adapterConfigTransfer);
    }

}
