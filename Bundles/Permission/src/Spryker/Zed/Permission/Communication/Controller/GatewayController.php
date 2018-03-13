<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Communication\Controller;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Permission\Business\PermissionFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findAllAction(): PermissionCollectionTransfer
    {
        return $this->getFacade()->findAll();
    }
}
