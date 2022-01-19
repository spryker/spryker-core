<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;

class DefaultAclQueryScope implements AclQueryScopeInterface
{
    /**
     * @var string
     */
    protected const CONDITION_EMPTY_COLLECTION = '0=1';

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     */
    public function __construct(AclEntityMetadataReaderInterface $aclEntityMetadataReader)
    {
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
    }

    /**
     * @param string $scope
     *
     * @return bool
     */
    public function isSupported(string $scope): bool
    {
        return $scope === AclEntityConstants::SCOPE_DEFAULT;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleOnSelectQuery(ModelCriteria $query, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): ModelCriteria
    {
        if ($this->isReadableQuery($query)) {
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
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleOnUpdateQuery(ModelCriteria $query, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): ModelCriteria
    {
        if ($this->isUpdatableQuery($query)) {
            return $query;
        }

        return $query->where(static::CONDITION_EMPTY_COLLECTION);
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyAclRuleOnDeleteQuery(ModelCriteria $query, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): ModelCriteria
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
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return bool
     */
    public function isReadableQuery(ModelCriteria $query): bool
    {
        $entityDefaultOperationMask = $this->aclEntityMetadataReader->getDefaultOperationMaskForEntityClass(
            $query->getModelName(),
        );

        return ($entityDefaultOperationMask & AclEntityConstants::OPERATION_MASK_READ) > 0;
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
        $entityDefaultOperationMask = $this->aclEntityMetadataReader->getDefaultOperationMaskForEntityClass(
            $query->getModelName(),
        );

        return ($entityDefaultOperationMask & AclEntityConstants::OPERATION_MASK_UPDATE) > 0;
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
        $entityDefaultOperationMask = $this->aclEntityMetadataReader->getDefaultOperationMaskForEntityClass(
            $query->getModelName(),
        );

        return ($entityDefaultOperationMask & AclEntityConstants::OPERATION_MASK_DELETE) > 0;
    }
}
