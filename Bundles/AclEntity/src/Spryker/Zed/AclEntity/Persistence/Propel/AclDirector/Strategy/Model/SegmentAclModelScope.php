<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use InvalidArgumentException;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Service\AclEntity\AclEntityServiceInterface;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class SegmentAclModelScope implements AclModelScopeInterface
{
    /**
     * @uses \Spryker\Zed\AclEntityDataImport\Business\DataSet\AclEntitySegmentConnectorDataSetInterface::FK_ACL_ENTITY_SEGMENT
     *
     * @var string
     */
    protected const FK_ACL_ENTITY_SEGMENT = 'fk_acl_entity_segment';

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
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function isCreatable(
        ActiveRecordInterface $entity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): bool {
        $aclEntityRuleCollectionTransfer = $this->getFilteredAclEntityRules(
            $aclEntityRuleCollectionTransfer,
            get_class($entity),
            AclEntityConstants::OPERATION_MASK_CREATE,
        );

        $aclEntitySegmentIds = array_map(
            function (AclEntityRuleTransfer $aclEntityRuleTransfer): int {
                return $aclEntityRuleTransfer->getIdAclEntitySegmentOrFail();
            },
            $aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy(),
        );

        $aclEntitySegmentGetter = $this->aclEntityService->generateSegmentConnectorGetter(get_class($entity));
        $callable = [$entity, $aclEntitySegmentGetter];

        if (!is_callable($callable)) {
            throw new InvalidArgumentException(sprintf('Expected a valid callable, %s given.', gettype($callable)));
        }

        /** @var \Orm\Zed\AclEntity\Persistence\SpyAclEntityRule $aclEntitySegment */
        foreach (call_user_func($callable) as $aclEntitySegment) {
            if (in_array($aclEntitySegment->getFkAclEntitySegment(), $aclEntitySegmentIds)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    public function isUpdatable(
        ActiveRecordInterface $entity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): bool {
        $aclEntityRuleCollectionTransfer = $this->getFilteredAclEntityRules(
            $aclEntityRuleCollectionTransfer,
            get_class($entity),
            AclEntityConstants::OPERATION_MASK_UPDATE,
        );

        return $this->inSegment($entity, $this->getAclEntitySegmentIds($aclEntityRuleCollectionTransfer));
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    public function isDeletable(
        ActiveRecordInterface $entity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): bool {
        $aclEntityRuleCollectionTransfer = $this->getFilteredAclEntityRules(
            $aclEntityRuleCollectionTransfer,
            get_class($entity),
            AclEntityConstants::OPERATION_MASK_DELETE,
        );

        return $this->inSegment($entity, $this->getAclEntitySegmentIds($aclEntityRuleCollectionTransfer));
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    public function isReadable(
        ActiveRecordInterface $entity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): bool {
        $aclEntityRuleCollectionTransfer = $this->getFilteredAclEntityRules(
            $aclEntityRuleCollectionTransfer,
            get_class($entity),
            AclEntityConstants::OPERATION_MASK_READ,
        );

        return $this->inSegment($entity, $this->getAclEntitySegmentIds($aclEntityRuleCollectionTransfer));
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param array<int> $segmentIds
     *
     * @return bool
     */
    protected function inSegment(ActiveRecordInterface $entity, array $segmentIds): bool
    {
        $segmentClass = $this->aclEntityService->generateSegmentConnectorClassName(get_class($entity));
        $segmentQuery = PropelQuery::from($segmentClass);

        $entityForeignKeyColumn = $this->aclEntityService->generateSegmentConnectorReferenceColumnName(
            PropelQuery::from(get_class($entity))->getTableMapOrFail()->getNameOrFail(),
        );

        $segmentQuery
            ->filterBy(
                $segmentQuery->getTableMapOrFail()->getColumn(static::FK_ACL_ENTITY_SEGMENT)->getPhpNameOrFail(),
                $segmentIds,
                Criteria::IN,
            )
            ->filterBy(
                $segmentQuery->getTableMapOrFail()->getColumn($entityForeignKeyColumn)->getPhpNameOrFail(),
                $entity->getPrimaryKey(),
            );

        return $segmentQuery->count() > 0;
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

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return array<int>
     */
    protected function getAclEntitySegmentIds(AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): array
    {
        return array_map(
            function (AclEntityRuleTransfer $aclEntityRuleTransfer): int {
                return $aclEntityRuleTransfer->getIdAclEntitySegmentOrFail();
            },
            $aclEntityRuleCollectionTransfer->getAclEntityRules()->getArrayCopy(),
        );
    }
}
