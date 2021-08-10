<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use InvalidArgumentException;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Map\TableMap;
use Spryker\Service\AclEntity\AclEntityServiceInterface;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface;

class SegmentScopeAclQueryDirectorStrategy implements AclQueryDirectorStrategyInterface
{
    /**
     * @uses \Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntitySegmentConnectorDataSetInterface::FK_ACL_ENTITY_SEGMENT
     */
    public const FK_ACL_ENTITY_SEGMENT = 'fk_acl_entity_segment';
    protected const SEGMENT_JOIN_CONDITION_TEMPLATE = '%s IN ?';

    /**
     * @var \Spryker\Service\AclEntity\AclEntityServiceInterface
     */
    protected $aclEntityService;

    /**
     * @var \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    protected $aclEntityRuleCollectionTransfer;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface
     */
    protected $aclEntityRuleCollectionTransferFilter;

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param \Spryker\Service\AclEntity\AclEntityServiceInterface $aclEntityService
     * @param \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter
     */
    public function __construct(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        AclEntityServiceInterface $aclEntityService,
        AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter
    ) {
        $this->aclEntityService = $aclEntityService;
        $this->aclEntityRuleCollectionTransfer = $aclEntityRuleCollectionTransfer;
        $this->aclEntityRuleCollectionTransferFilter = $aclEntityRuleCollectionTransferFilter;
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
        $aclEntitySegmentIds = array_map(
            function (AclEntityRuleTransfer $aclEntityRuleTransfer): int {
                return $aclEntityRuleTransfer->getIdAclEntitySegmentOrFail();
            },
            $this->aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy()
        );

        $segmentRelationName = $this->aclEntityService->generateSegmentConnectorRelationName($query->getModelShortName());
        $segmentClassName = $this->aclEntityService->generateSegmentConnectorClassName($query->getModelName());
        $segmentTableMap = PropelQuery::from($segmentClassName)->getTableMap();

        $query
            ->join($segmentRelationName)
            ->addJoinCondition(
                $segmentTableMap->getPhpName(),
                $this->generateSegmentJoinCondition($segmentTableMap),
                $aclEntitySegmentIds
            );

        return $query;
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
        $updatePermissionAclEntityRuleSegmentIds = array_map(
            function (AclEntityRuleTransfer $aclEntityRuleTransfer): int {
                return $aclEntityRuleTransfer->getIdAclEntitySegmentOrFail();
            },
            $this->aclEntityRuleCollectionTransferFilter->filterByPermissionMask(
                $this->aclEntityRuleCollectionTransfer,
                AclEntityConstants::OPERATION_MASK_UPDATE
            )->getAclEntityRules()->getArrayCopy()
        );
        // Propel does not support joins for update queries through query builder (@see \Propel\Runtime\ActiveQuery\ModelCriteria::update)
        // Limit update scope with additional IN condition
        /** @var \Propel\Runtime\Map\ColumnMap $primaryKey */
        $primaryKey = current($query->getTableMap()->getPrimaryKeys());
        if (!$updatePermissionAclEntityRuleSegmentIds) {
            return $query->filterBy($primaryKey->getPhpName(), null, Criteria::ISNULL);
        }

        $targetEntityIds = $this->getAccessibleTargetEntityIds($query, $updatePermissionAclEntityRuleSegmentIds);

        return $query->filterBy($primaryKey->getPhpName(), $targetEntityIds, Criteria::IN);
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
        // Propel tries to delete joined tables data on delete query as well.
        // Limit update scope with additional IN condition
        $deletePermissionAclEntityRuleSegmentIds = array_map(
            function (AclEntityRuleTransfer $aclEntityRuleTransfer): int {
                return $aclEntityRuleTransfer->getIdAclEntitySegmentOrFail();
            },
            $this->aclEntityRuleCollectionTransferFilter->filterByPermissionMask(
                $this->aclEntityRuleCollectionTransfer,
                AclEntityConstants::OPERATION_MASK_DELETE
            )->getAclEntityRules()->getArrayCopy()
        );
        /** @var \Propel\Runtime\Map\ColumnMap $primaryKey */
        $primaryKey = current($query->getTableMap()->getPrimaryKeys());
        if (!$deletePermissionAclEntityRuleSegmentIds) {
            return $query->filterBy($primaryKey->getPhpName(), null, Criteria::ISNULL);
        }
        $targetEntityIds = $this->getAccessibleTargetEntityIds($query, $deletePermissionAclEntityRuleSegmentIds);
        if (!$targetEntityIds) {
            return $query->filterBy($primaryKey->getPhpName(), null, Criteria::ISNULL);
        }

        return $query->filterBy($primaryKey->getPhpName(), $targetEntityIds, Criteria::IN);
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function isCreatable(ActiveRecordInterface $entity): bool
    {
        $aclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferFilter
            ->filterByScopeEntityClassAndPermissionMask(
                $this->aclEntityRuleCollectionTransfer,
                AclEntityConstants::SCOPE_SEGMENT,
                get_class($entity),
                AclEntityConstants::OPERATION_MASK_CREATE
            );

        $aclEntitySegmentIds = array_map(
            function (AclEntityRuleTransfer $aclEntityRuleTransfer): int {
                return $aclEntityRuleTransfer->getIdAclEntitySegmentOrFail();
            },
            $aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy()
        );

        $aclEntitySegmentGetter = $this->aclEntityService->generateSegmentConnectorGetter(get_class($entity));
        $callable = [$entity, $aclEntitySegmentGetter];

        if (!is_callable($callable)) {
            throw new InvalidArgumentException(sprintf('Expected a valid callable, %s given.', gettype($callable)));
        }

        foreach (call_user_func($callable) as $aclEntitySegment) {
            if (in_array($aclEntitySegment->getFkAclEntitySegment(), $aclEntitySegmentIds)) {
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
            if ($this->segmentHasEntity($entity, $aclEntityRuleTransfer->getIdAclEntitySegmentOrFail())) {
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
            if ($this->segmentHasEntity($entity, $aclEntityRuleTransfer->getIdAclEntitySegmentOrFail())) {
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
    public function isDeletableQuery(ModelCriteria $query): bool
    {
        foreach ($this->aclEntityRuleCollectionTransfer->getAclEntityRules() as $aclEntityRuleTransfer) {
            if ($aclEntityRuleTransfer->getPermissionMaskOrFail() & AclEntityConstants::OPERATION_MASK_DELETE) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param int $segmentId
     *
     * @return bool
     */
    public function segmentHasEntity(ActiveRecordInterface $entity, int $segmentId): bool
    {
        $segmentClass = $this->aclEntityService->generateSegmentConnectorClassName(get_class($entity));
        $segmentQuery = PropelQuery::from($segmentClass);

        $entityForeignKeyColumn = $this->aclEntityService->generateSegmentConnectorReferenceColumnName(
            PropelQuery::from(get_class($entity))->getTableMap()->getName()
        );

        $segmentQuery
            ->filterBy(
                $segmentQuery->getTableMap()->getColumn(self::FK_ACL_ENTITY_SEGMENT)->getPhpName(),
                $segmentId
            )
            ->filterBy(
                $segmentQuery->getTableMap()->getColumn($entityForeignKeyColumn)->getPhpName(),
                $entity->getPrimaryKey()
            );

        return $segmentQuery->count() > 0;
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param int[] $segmentIds
     *
     * @return int[]
     */
    protected function getAccessibleTargetEntityIds(ModelCriteria $query, array $segmentIds): array
    {
        $segmentTableQuery = PropelQuery::from(
            $this->aclEntityService->generateSegmentConnectorClassName($query->getModelName())
        );
        $segmentEntities = $segmentTableQuery
            ->filterBy(
                $segmentTableQuery->getTableMap()->getColumn(self::FK_ACL_ENTITY_SEGMENT)->getPhpName(),
                $segmentIds,
                Criteria::IN
            )
            ->find();

        return array_map(
            function (ActiveRecordInterface $segmentEntity) use ($query): int {
                $callable = [
                    $segmentEntity,
                    $this->aclEntityService->generateSegmentConnectorReferenceGetter($query->getModelName()),
                ];

                if (!is_callable($callable)) {
                    throw new InvalidArgumentException(sprintf('Expected a valid callable, %s given.', gettype($callable)));
                }

                return call_user_func($callable);
            },
            $segmentEntities->getArrayCopy()
        );
    }

    /**
     * @param \Propel\Runtime\Map\TableMap $segmentTableMap
     *
     * @return string
     */
    protected function generateSegmentJoinCondition(TableMap $segmentTableMap): string
    {
        return sprintf(
            self::SEGMENT_JOIN_CONDITION_TEMPLATE,
            $segmentTableMap->getColumn(static::FK_ACL_ENTITY_SEGMENT)->getFullyQualifiedName()
        );
    }
}
