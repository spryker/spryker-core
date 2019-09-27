<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ConfigurableBundleStorage\ConfigurableBundleStorageFactory getFactory()
 */
class ConfigurableBundleStorageClient extends AbstractClient implements ConfigurableBundleStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorage(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateStorageTransfer
    {
        return $this->getFactory()
            ->createConfigurableBundleStorageReader()
            ->findConfigurableBundleTemplateStorage($idConfigurableBundleTemplate);
    }
}
