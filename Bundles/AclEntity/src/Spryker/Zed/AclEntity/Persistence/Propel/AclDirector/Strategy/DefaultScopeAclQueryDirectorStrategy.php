<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;

class DefaultScopeAclQueryDirectorStrategy implements AclQueryDirectorStrategyInterface
{
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
    public function applyAclRuleOnUpdateQuery(ModelCriteria $query): ModelCriteria
    {
        if ($this->isUpdatableQuery($query)) {
            return $query;
        }

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
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return bool
     */
    public function isCreatable(ActiveRecordInterface $entity): bool
    {
        $entityDefaultOperationMask = $this->aclEntityMetadataReader->getDefaultOperationMaskForEntityClass(
            get_class($entity),
        );
        if ($entityDefaultOperationMask & AclEntityConstants::OPERATION_MASK_CREATE) {
            return true;
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
        $entityDefaultOperationMask = $this->aclEntityMetadataReader->getDefaultOperationMaskForEntityClass(
            get_class($entity),
        );
        if ($entityDefaultOperationMask & AclEntityConstants::OPERATION_MASK_UPDATE) {
            return true;
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
        $entityDefaultOperationMask = $this->aclEntityMetadataReader->getDefaultOperationMaskForEntityClass(
            get_class($entity),
        );
        if ($entityDefaultOperationMask & AclEntityConstants::OPERATION_MASK_DELETE) {
            return true;
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
