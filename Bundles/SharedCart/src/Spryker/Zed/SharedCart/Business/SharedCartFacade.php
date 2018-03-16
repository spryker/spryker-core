<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface getRepository()
 */
class SharedCartFacade extends AbstractFacade implements SharedCartFacadeInterface
{
    /**
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser($idCompanyUser): PermissionCollectionTransfer
    {
        return $this->getRepository()->findPermissionsByIdCompanyUser($idCompanyUser);
    }
}
