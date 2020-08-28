<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\GroupTransfer;
use Orm\Zed\Acl\Persistence\SpyAclGroup;

class AclMapper
{
    /**
     * @param \Orm\Zed\Acl\Persistence\SpyAclGroup $aclGroup
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function mapAclGroupEntityToGroupTransfer(SpyAclGroup $aclGroup, GroupTransfer $groupTransfer): GroupTransfer
    {
        $groupTransfer->fromArray($aclGroup->toArray(), true);

        return $groupTransfer;
    }
}
