<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class GlobalScopeAclQueryDirectorStrategy implements AclQueryDirectorStrategyInterface
{
    /**
     * @var \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    protected $aclEntityRuleCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     */
    public function __construct(AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer)
    {
        $this->aclEntityRuleCollectionTransfer = $aclEntityRuleCollectionTransfer;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleOnSelectQuery(ModelCriteria $query): ModelCriteria
    {
        if ($this->isReadableQuery($query)) {
            return $query;
        }

        // empty result set in case when "read" is not permitted
        return $query->where(AclQueryDirectorStrategyInterface::CONDITION_EMPTY_COLLECTION);
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleOnUpdateQuery(ModelCriteria $query): ModelCriteria
    {
        if ($this->isUpdatableQuery($query)) {
            return $query;
        }

        /** @var \Propel\Runtime\Map\ColumnMap $primaryKeyColumn */
        $primaryKeyColumn = current($query->getTableMap()->getPrimaryKeys());

        return $query->filterBy($primaryKeyColumn->getPhpName(), null, Criteria::ISNULL);
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleOnDeleteQuery(ModelCriteria $query): ModelCriteria
    {
        if ($this->isDeletableQuery($query)) {
            return $query;
        }

        /** @var \Propel\Runtime\Map\ColumnMap $primaryKeyColumn */
        $primaryKeyColumn = current($query->getTableMap()->getPrimaryKeys());

        return $query->filterBy($primaryKeyColumn->getPhpName(), null, Criteria::ISNULL);
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleForDeleteQuery(ModelCriteria $query): ModelCriteria
    {
        if ($this->isDeletableQuery($query)) {
            return $query;
        }

        /** @var \Propel\Runtime\Map\ColumnMap $primaryKeyColumn */
        $primaryKeyColumn = current($query->getTableMap()->getPrimaryKeys());

        return $query->filterBy($primaryKeyColumn->getPhpName(), null, Criteria::ISNULL);
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return bool
     */
    public function isCreatable(ActiveRecordInterface $entity): bool
    {
        foreach ($this->aclEntityRuleCollectionTransfer->getAclEntityRules() as $aclEntityRuleTransfer) {
            if ($aclEntityRuleTransfer->getPermissionMaskOrFail() & AclEntityConstants::OPERATION_MASK_CREATE) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return bool
     */
    public function isUpdatable(ActiveRecordInterface $entity): bool
    {
        foreach ($this->aclEntityRuleCollectionTransfer->getAclEntityRules() as $aclEntityRuleTransfer) {
            if ($aclEntityRuleTransfer->getPermissionMaskOrFail() & AclEntityConstants::OPERATION_MASK_UPDATE) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return bool
     */
    public function isDeletable(ActiveRecordInterface $entity): bool
    {
        foreach ($this->aclEntityRuleCollectionTransfer->getAclEntityRules() as $aclEntityRuleTransfer) {
            if ($aclEntityRuleTransfer->getPermissionMaskOrFail() & AclEntityConstants::OPERATION_MASK_DELETE) {
                return true;
            }
        }

        return false;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return bool
     */
    public function isReadableQuery(ModelCriteria $query): bool
    {
        return true;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return bool
     */
    public function isUpdatableQuery(ModelCriteria $query): bool
    {
        foreach ($this->aclEntityRuleCollectionTransfer->getAclEntityRules() as $aclEntityRuleTransfer) {
            if ($aclEntityRuleTransfer->getPermissionMaskOrFail() & AclEntityConstants::OPERATION_MASK_UPDATE) {
                return true;
            }
        }

        return false;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return bool
     */
    public function isDeletableQuery(ModelCriteria $query): bool
    {
        foreach ($this->aclEntityRuleCollectionTransfer->getAclEntityRules() as $aclEntityRuleTransfer) {
            if ($aclEntityRuleTransfer->getPermissionMaskOrFail() & AclEntityConstants::OPERATION_MASK_DELETE) {
                return true;
            }
        }

        return false;
    }
}
