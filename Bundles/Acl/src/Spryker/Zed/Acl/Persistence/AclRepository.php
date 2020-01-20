<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Persistence;

use Generated\Shared\Transfer\GroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Acl\Persistence\AclPersistenceFactory getFactory()
 */
class AclRepository extends AbstractRepository implements AclRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\GroupCriteriaFilterTransfer $groupCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer|null
     */
    public function findGroup(GroupCriteriaFilterTransfer $groupCriteriaFilterTransfer): ?GroupTransfer
    {
        $aclGroupQuery = $this->getFactory()->createGroupQuery();
        $aclGroupQuery = $this->applyGroupFilters($aclGroupQuery, $groupCriteriaFilterTransfer);

        $aclGroupEntity = $aclGroupQuery->findOne();

        if (!$aclGroupEntity) {
            return null;
        }

        return $this->getFactory()
            ->createAclMapper()
            ->mapAclGroupEntityToGroupTransfer($aclGroupEntity, new GroupTransfer());
    }

    /**
     * @param \Orm\Zed\Acl\Persistence\SpyAclGroupQuery $aclGroupQuery
     * @param \Generated\Shared\Transfer\GroupCriteriaFilterTransfer $groupCriteriaFilterTransfer
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    protected function applyGroupFilters(SpyAclGroupQuery $aclGroupQuery, GroupCriteriaFilterTransfer $groupCriteriaFilterTransfer): SpyAclGroupQuery
    {
        if ($groupCriteriaFilterTransfer->getReference()) {
            $aclGroupQuery->filterByReference($groupCriteriaFilterTransfer->getReference());
        }

        return $aclGroupQuery;
    }
}
