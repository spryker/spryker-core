<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;

class ConfigurableBundlesRestApiToConfigurableBundleStorageClientBridge implements ConfigurableBundlesRestApiToConfigurableBundleStorageClientInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\ConfigurableBundleStorageClientInterface
     */
    protected $configurableBundleStorageClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleStorage\ConfigurableBundleStorageClientInterface $configurableBundleStorageClient
     */
    public function __construct($configurableBundleStorageClient)
    {
        $this->configurableBundleStorageClient = $configurableBundleStorageClient;
    }

    /**
     * @param string $configurableBundleTemplateUuid
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorageByUuid(
        string $configurableBundleTemplateUuid,
        string $localeName
    ): ?ConfigurableBundleTemplateStorageTransfer {
        return $this->configurableBundleStorageClient
            ->findConfigurableBundleTemplateStorageByUuid($configurableBundleTemplateUuid, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageFilterTransfer $configurableBundleTemplateStorageFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[]
     */
    public function getConfigurableBundleTemplateStorageCollection(
        ConfigurableBundleTemplateStorageFilterTransfer $configurableBundleTemplateStorageFilterTransfer
    ): array {
        return $this->configurableBundleStorageClient
            ->getConfigurableBundleTemplateStorageCollection($configurableBundleTemplateStorageFilterTransfer);
    }
}
