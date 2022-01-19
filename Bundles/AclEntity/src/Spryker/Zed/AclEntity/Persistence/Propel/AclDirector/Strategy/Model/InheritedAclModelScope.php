<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Reader\AclRelationReaderInterface;
use Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface;

class InheritedAclModelScope implements AclModelScopeInterface
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface
     */
    protected $aclEntityRuleCollectionTransferFilter;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface
     */
    protected $aclEntityMetadataReader;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Reader\AclRelationReaderInterface
     */
    protected $aclRelationReader;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\AclModelScopeInterface
     */
    protected $segmentAclModelScope;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Filter\AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Reader\AclRelationReaderInterface $aclRelationReader
     * @param \Spryker\Zed\AclEntity\Persistence\Reader\AclEntityMetadataReaderInterface $aclEntityMetadataReader
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\Model\AclModelScopeInterface $segmentAclModelScope
     */
    public function __construct(
        AclEntityRuleCollectionTransferFilterInterface $aclEntityRuleCollectionTransferFilter,
        AclRelationReaderInterface $aclRelationReader,
        AclEntityMetadataReaderInterface $aclEntityMetadataReader,
        AclModelScopeInterface $segmentAclModelScope
    ) {
        $this->aclEntityRuleCollectionTransferFilter = $aclEntityRuleCollectionTransferFilter;
        $this->aclRelationReader = $aclRelationReader;
        $this->aclEntityMetadataReader = $aclEntityMetadataReader;
        $this->segmentAclModelScope = $segmentAclModelScope;
    }

    /**
     * @param string $scope
     *
     * @return bool
     */
    public function isSupported(string $scope): bool
    {
        return $scope === AclEntityConstants::SCOPE_INHERITED;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return bool
     */
    public function isCreatable(ActiveRecordInterface $entity, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): bool
    {
        foreach ($this->getGroupedAclEntityRulesByAclGroupId($aclEntityRuleCollectionTransfer) as $aclEntityRuleCollectionTransfer) {
            $createAclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferFilter
                ->filterByEntityClassAndPermissionMask(
                    $aclEntityRuleCollectionTransfer,
                    get_class($entity),
                    AclEntityConstants::OPERATION_MASK_CREATE,
                );
            if ($createAclEntityRuleCollectionTransfer->getAclEntityRules()->count() > 0) {
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
    public function isUpdatable(ActiveRecordInterface $entity, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): bool
    {
        foreach ($this->getGroupedAclEntityRulesByAclGroupId($aclEntityRuleCollectionTransfer) as $aclEntityRuleCollectionTransfer) {
            $updateAclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferFilter
                ->filterByEntityClassAndPermissionMask(
                    $aclEntityRuleCollectionTransfer,
                    get_class($entity),
                    AclEntityConstants::OPERATION_MASK_UPDATE,
                );
            if (
                $updateAclEntityRuleCollectionTransfer->getAclEntityRules()->count() > 0
                && $this->findReadableRoot($entity, $aclEntityRuleCollectionTransfer)
            ) {
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
    public function isDeletable(ActiveRecordInterface $entity, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): bool
    {
        foreach ($this->getGroupedAclEntityRulesByAclGroupId($aclEntityRuleCollectionTransfer) as $aclEntityRuleCollectionTransfer) {
            $deleteAclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferFilter
                ->filterByEntityClassAndPermissionMask(
                    $aclEntityRuleCollectionTransfer,
                    get_class($entity),
                    AclEntityConstants::OPERATION_MASK_DELETE,
                );
            if (
                $deleteAclEntityRuleCollectionTransfer->getAclEntityRules()->count() > 0
                && $this->findReadableRoot($entity, $aclEntityRuleCollectionTransfer)
            ) {
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
    public function isReadable(ActiveRecordInterface $entity, AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer): bool
    {
        foreach ($this->getGroupedAclEntityRulesByAclGroupId($aclEntityRuleCollectionTransfer) as $aclEntityRuleCollectionTransfer) {
            $deleteAclEntityRuleCollectionTransfer = $this->aclEntityRuleCollectionTransferFilter
                ->filterByEntityClassAndPermissionMask(
                    $aclEntityRuleCollectionTransfer,
                    get_class($entity),
                    AclEntityConstants::OPERATION_MASK_READ,
                );
            if (
                $deleteAclEntityRuleCollectionTransfer->getAclEntityRules()->count() > 0
                && $this->findReadableRoot($entity, $aclEntityRuleCollectionTransfer)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return array<\Generated\Shared\Transfer\AclEntityRuleCollectionTransfer>
     */
    protected function getGroupedAclEntityRulesByAclGroupId(
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): array {
        $result = [];
        foreach ($aclEntityRuleCollectionTransfer->getAclEntityRules() as $aclEntityRuleTransfer) {
            if (!isset($result[$aclEntityRuleTransfer->getIdAclRoleOrFail()])) {
                $result[$aclEntityRuleTransfer->getIdAclRoleOrFail()] = new AclEntityRuleCollectionTransfer();
            }
            $result[$aclEntityRuleTransfer->getIdAclRoleOrFail()]->addAclEntityRule($aclEntityRuleTransfer);
        }

        return $result;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface|null
     */
    protected function findReadableRoot(
        ActiveRecordInterface $entity,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): ?ActiveRecordInterface {
        $aclEntityMetadataTransfer = $this->aclEntityMetadataReader->getAclEntityMetadataTransferForEntityClass(
            get_class($entity),
        );
        foreach ($this->aclRelationReader->getRelationsByAclEntityMetadata($entity, $aclEntityMetadataTransfer) as $relation) {
            $readableRoot = $this->findRelationReadableRoot($relation, $aclEntityRuleCollectionTransfer);
            if ($readableRoot) {
                return $readableRoot;
            }
        }

        return null;
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $relation
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface|null
     */
    public function findRelationReadableRoot(
        ActiveRecordInterface $relation,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): ?ActiveRecordInterface {
        $aclEntityRules = $this->aclEntityRuleCollectionTransferFilter->filterByEntityClassAndPermissionMask(
            $aclEntityRuleCollectionTransfer,
            get_class($relation),
            AclEntityConstants::OPERATION_MASK_READ,
        );
        foreach ($aclEntityRules->getAclEntityRules() as $aclEntityRule) {
            if ($aclEntityRule->getScope() === AclEntityConstants::SCOPE_INHERITED) {
                return $this->findReadableRoot($relation, $aclEntityRuleCollectionTransfer);
            }
            if ($aclEntityRule->getScope() === AclEntityConstants::SCOPE_GLOBAL) {
                return $relation;
            }
            if (
                $aclEntityRule->getScope() === AclEntityConstants::SCOPE_SEGMENT
                && $this->segmentAclModelScope->isReadable($relation, $aclEntityRuleCollectionTransfer)
            ) {
                return $relation;
            }
        }

        return null;
    }
}
