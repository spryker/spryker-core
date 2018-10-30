<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Form\DataProvider;

use Spryker\Zed\Acl\Business\AclFacade;

class AclRoleFormDataProvider
{
    /**
     * @var \Spryker\Zed\Acl\Business\AclFacade
     */
    protected $aclFacade;

    /**
     * @param \Spryker\Zed\Acl\Business\AclFacade $aclFacade
     */
    public function __construct(AclFacade $aclFacade)
    {
        $this->aclFacade = $aclFacade;
    }

    /**
     * @param int $idAclRole
     *
     * @return array|null
     */
    public function getData($idAclRole)
    {
        $roleTransfer = $this->aclFacade->findRoleById($idAclRole);

        if (!$roleTransfer) {
            return null;
        }

        return $roleTransfer->toArray();
    }
}
