<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Persistence;

use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Acl\Persistence\AclPersistenceFactory getFactory()
 */
class AclRepository extends AbstractRepository implements AclRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\GroupCriteriaTransfer $groupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer|null
     */
    public function findGroup(GroupCriteriaTransfer $groupCriteriaTransfer): ?GroupTransfer
    {
        $aclGroupQuery = $this->getFactory()
            ->createGroupQuery()
            ->filterByArray($groupCriteriaTransfer->modifiedToArrayNotRecursiveCamelCased());

        $aclGroupEntity = $aclGroupQuery->findOne();

        if (!$aclGroupEntity) {
            return null;
        }

        return $this->getFactory()
            ->createAclMapper()
            ->mapAclGroupEntityToGroupTransfer($aclGroupEntity, new GroupTransfer());
    }
}
