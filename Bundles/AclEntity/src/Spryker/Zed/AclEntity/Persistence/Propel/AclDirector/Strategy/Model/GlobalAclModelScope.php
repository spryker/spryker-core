<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\AclEntity\AclEntityConstants;

class GlobalAclModelScope implements AclModelScopeInterface
{
    /**
     * @param string $scope
     *
     * @return bool
     */
    public function isSupported(string $scope): bool
    {
        return $scope === AclEntityConstants::SCOPE_GLOBAL;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    public function isCreatable(ActiveRecordInterface $entity, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): bool
    {
        return true;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    public function isUpdatable(ActiveRecordInterface $entity, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): bool
    {
        return true;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    public function isDeletable(ActiveRecordInterface $entity, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): bool
    {
        return true;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    public function isReadable(ActiveRecordInterface $entity, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): bool
    {
        return true;
    }
}
