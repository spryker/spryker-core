<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Dependency\Facade;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

class SharedCartToPermissionFacadeBridge implements SharedCartToPermissionFacadeInterface
{
    /**
     * @var \Spryker\Zed\Permission\Business\PermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @param \Spryker\Zed\Permission\Business\PermissionFacadeInterface $permissionFacade
     */
    public function __construct($permissionFacade)
    {
        $this->permissionFacade = $permissionFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findAll(): PermissionCollectionTransfer
    {
        return $this->permissionFacade->findAll();
    }

    /**
     * @return void
     */
    public function syncPermissionPlugins(): void
    {
        $this->permissionFacade->syncPermissionPlugins();
    }
}
