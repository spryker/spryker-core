<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder;

use Generated\Shared\Transfer\FlysystemConfigLocalTransfer;
use Spryker\Service\Flysystem\Model\Builder\Type\LocalTypeBuilder;

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
    protected function validateConfig()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        $adapterConfigTransfer->requirePath();
        $adapterConfigTransfer->requireRoot();
    }

    /**
     * @return \Spryker\Service\Flysystem\Model\Builder\FilesystemBuilderInterface
     */
    protected function createFlysystemBuilder()
    {
        $adapterConfigTransfer = $this->buildAdapterConfig();

        return new LocalTypeBuilder($this->config, $adapterConfigTransfer);
    }

}
