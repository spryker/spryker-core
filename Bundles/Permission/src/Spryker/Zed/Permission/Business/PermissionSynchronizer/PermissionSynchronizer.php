<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business\PermissionSynchronizer;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Client\Permission\PermissionClientInterface;
use Spryker\Zed\Permission\Business\PermissionFinder\PermissionFinderInterface;
use Spryker\Zed\Permission\Persistence\PermissionEntityManagerInterface;

class PermissionSynchronizer implements PermissionSynchronizerInterface
{
    /**
     * @var \Spryker\Client\Permission\PermissionClientInterface
     */
    protected $permissionClient;

    /**
     * @var \Spryker\Zed\Permission\Business\PermissionFinder\PermissionFinderInterface
     */
    protected $permissionFinder;

    /**
     * @var \Spryker\Zed\Permission\Persistence\PermissionEntityManagerInterface
     */
    protected $permissionEntityManager;

    /**
     * @param \Spryker\Client\Permission\PermissionClientInterface $permissionClient
     * @param \Spryker\Zed\Permission\Business\PermissionFinder\PermissionFinderInterface $permissionFinder
     * @param \Spryker\Zed\Permission\Persistence\PermissionEntityManagerInterface $permissionEntityManager
     */
    public function __construct(
        PermissionClientInterface $permissionClient,
        PermissionFinderInterface $permissionFinder,
        PermissionEntityManagerInterface $permissionEntityManager
    ) {
        $this->permissionClient = $permissionClient;
        $this->permissionFinder = $permissionFinder;
        $this->permissionEntityManager = $permissionEntityManager;
    }

    /**
     * @return void
     */
    public function sync(): void
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();
        $permissionCollectionTransfer = $this->addPermissionCollection(
            $permissionCollectionTransfer,
            $this->permissionClient->getRegisteredPermissions()
        );

        $permissionCollectionTransfer = $this->addPermissionCollection(
            $permissionCollectionTransfer,
            $this->permissionFinder->getRegisteredPermissions()
        );

        $this->permissionEntityManager->upsertPermissionCollection($permissionCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransferA
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransferB
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function addPermissionCollection(PermissionCollectionTransfer $permissionCollectionTransferA, PermissionCollectionTransfer $permissionCollectionTransferB)
    {
        foreach ($permissionCollectionTransferB->getPermissions() as $permissionTransfer) {
            $permissionCollectionTransferA->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransferA;
    }
}
