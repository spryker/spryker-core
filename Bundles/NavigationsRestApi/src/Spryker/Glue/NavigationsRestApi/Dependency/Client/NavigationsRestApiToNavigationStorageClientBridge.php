<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi\Dependency\Client;

use Generated\Shared\Transfer\NavigationStorageTransfer;

class NavigationsRestApiToNavigationStorageClientBridge implements NavigationsRestApiToNavigationStorageClientInterface
{
    /**
     * @var \Spryker\Client\NavigationStorage\NavigationStorageClientInterface
     */
    protected $navigationStorageClient;

    /**
     * @param \Spryker\Client\NavigationStorage\NavigationStorageClientInterface $navigationStorageClient
     */
    public function __construct($navigationStorageClient)
    {
        $this->navigationStorageClient = $navigationStorageClient;
    }

    /**
     * @param string $navigationKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\NavigationStorageTransfer|null
     */
    public function findNavigationTreeByKey($navigationKey, $localeName): ?NavigationStorageTransfer
    {
        return $this->navigationStorageClient->findNavigationTreeByKey($navigationKey, $localeName);
    }
}
