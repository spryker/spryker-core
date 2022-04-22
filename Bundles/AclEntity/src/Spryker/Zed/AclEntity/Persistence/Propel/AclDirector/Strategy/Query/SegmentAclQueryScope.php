<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Query;

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

class SegmentAclQueryScope implements AclQueryScopeInterface
{
    /**
     * @uses \Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntitySegmentConnectorDataSetInterface::FK_ACL_ENTITY_SEGMENT
     *
     * @var string
     */
    public const FK_ACL_ENTITY_SEGMENT = 'fk_acl_entity_segment';

    /**
     * @var string
     */
    protected const SEGMENT_JOIN_CONDITION_TEMPLATE = '%s IN ?';

    /**
     * @var \Spryker\Service\AclEntity\AclEntityServiceInterface
     */
    protected $aclEntityService;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface
     */
    protected $aclEntityRuleCollectionTransferFilter;

    /**
     * @param \Spryker\Service\AclEntity\AclEntityServiceInterface $aclEntityService
     * @param \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter
     */
    public function __construct(
        AclEntityServiceInterface $aclEntityService,
        AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter
    ) {
        $this->aclEntityService = $aclEntityService;
        $this->aclEntityRuleCollectionTransferFilter = $aclEntityRuleCollectionTransferFilter;
    }

    /**
     * @param string $scope
     *
     * @return bool
     */
    public function isSupported(string $scope): bool
    {
        return $scope === AclEntityConstants::SCOPE_SEGMENT;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    public function applyAclRuleOnSelectQuery(ModelCriteria $query, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): ModelCriteria
    {
        $aclEntityRuleCollectionTransfer = $this->getFilteredAclEntityRules(
            $aclEntityRuleCollectionTransfer,
            $query->getModelName(),
            AclEntityConstants::OPERATION_MASK_READ,
        );
        $aclEntitySegmentIds = array_map(
            function (AclEntityRuleTransfer $aclEntityRuleTransfer): int {
                return $aclEntityRuleTransfer->getIdAclEntitySegmentOrFail();
            },
            $aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy(),
        );

        $segmentRelationName = $this->aclEntityService->generateSegmentConnectorRelationName($query->getModelShortName());
        $segmentClassName = $this->aclEntityService->generateSegmentConnectorClassName($query->getModelName());
        $segmentTableMap = PropelQuery::from($segmentClassName)->getTableMap();

        $query
            ->join($segmentRelationName)
            ->addJoinCondition(
                $segmentTableMap->getPhpName(),
                $this->generateSegmentJoinCondition($segmentTableMap),
                $aclEntitySegmentIds,
            );

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    public function applyAclRuleOnUpdateQuery(ModelCriteria $query, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): ModelCriteria
    {
        $aclEntityRuleCollectionTransfer = $this->getFilteredAclEntityRules(
            $aclEntityRuleCollectionTransfer,
            $query->getModelName(),
            AclEntityConstants::OPERATION_MASK_UPDATE,
        );

        $updatePermissionAclEntityRuleSegmentIds = array_map(
            function (AclEntityRuleTransfer $aclEntityRuleTransfer): int {
                return $aclEntityRuleTransfer->getIdAclEntitySegmentOrFail();
            },
            $aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy(),
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
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    public function applyAclRuleOnDeleteQuery(ModelCriteria $query, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): ModelCriteria
    {
        $aclEntityRuleCollectionTransfer = $this->getFilteredAclEntityRules(
            $aclEntityRuleCollectionTransfer,
            $query->getModelName(),
            AclEntityConstants::OPERATION_MASK_DELETE,
        );
        // Propel tries to delete joined tables data on delete query as well.
        // Limit update scope with additional IN condition
        $deletePermissionAclEntityRuleSegmentIds = array_map(
            function (AclEntityRuleTransfer $aclEntityRuleTransfer): int {
                return $aclEntityRuleTransfer->getIdAclEntitySegmentOrFail();
            },
            $aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy(),
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
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $query
     * @param array<int> $segmentIds
     *
     * @return array<int>
     */
    protected function getAccessibleTargetEntityIds(ModelCriteria $query, array $segmentIds): array
    {
        $segmentTableQuery = PropelQuery::from(
            $this->aclEntityService->generateSegmentConnectorClassName($query->getModelName()),
        );
        $segmentEntities = $segmentTableQuery
            ->filterBy(
                $segmentTableQuery->getTableMap()->getColumn(static::FK_ACL_ENTITY_SEGMENT)->getPhpName(),
                $segmentIds,
                Criteria::IN,
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
            $segmentEntities->getArrayCopy(),
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
            static::SEGMENT_JOIN_CONDITION_TEMPLATE,
            $segmentTableMap->getColumn(static::FK_ACL_ENTITY_SEGMENT)->getFullyQualifiedName(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     * @param string $entityClass
     * @param int $operationMask
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    protected function getFilteredAclEntityRules(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer,
        string $entityClass,
        int $operationMask
    ): AclEntityRuleCollectionTransfer {
        return $this->aclEntityRuleCollectionTransferFilter->filterByScopeEntityClassAndPermissionMask(
            $aclEntityRuleCollectionTransfer,
            AclEntityConstants::SCOPE_SEGMENT,
            $entityClass,
            $operationMask,
        );
    }
}
