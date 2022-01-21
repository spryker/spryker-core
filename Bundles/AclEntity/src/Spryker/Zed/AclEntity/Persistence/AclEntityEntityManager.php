<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence;

use Generated\Shared\Transfer\AclEntityRuleRequestTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclEntitySegmentTransfer;
use Orm\Zed\AclEntity\Persistence\SpyAclEntityRule;
use Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\AclEntity\Persistence\AclEntityPersistenceFactory getFactory()
 */
class AclEntityEntityManager extends AbstractEntityManager implements AclEntityEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentTransfer
     */
    public function createAclEntitySegment(AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer): AclEntitySegmentTransfer
    {
        $aclEntitySegmentMapper = $this->getFactory()
            ->createAclEntitySegmentMapper();

        $aclEntitySegmentEntity = $aclEntitySegmentMapper
            ->mapAclEntitySegmentRequestTransferToEntity($aclEntitySegmentRequestTransfer, new SpyAclEntitySegment());

        $aclEntitySegmentEntity->save();

        $aclEntitySegmentRequestTransfer->setIdAclEntitySegment($aclEntitySegmentEntity->getIdAclEntitySegment());
        $this->createAclEntitySegmentRelations($aclEntitySegmentRequestTransfer);

        return $aclEntitySegmentMapper->mapAclEntitySegmentEntityToTransfer($aclEntitySegmentEntity, new AclEntitySegmentTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentTransfer $aclEntitySegmentTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentTransfer
     */
    public function updateAclEntitySegment(AclEntitySegmentTransfer $aclEntitySegmentTransfer): AclEntitySegmentTransfer
    {
        /** @var \Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment $aclEntitySegmentEntity */
        $aclEntitySegmentEntity = $this->getFactory()
            ->createAclEntitySegmentQuery()
            ->filterByIdAclEntitySegment($aclEntitySegmentTransfer->getIdAclEntitySegmentOrFail())
            ->findOne();

        $aclEntitySegmentMapper = $this->getFactory()
            ->createAclEntitySegmentMapper();

        $aclEntitySegmentEntity = $aclEntitySegmentMapper
            ->mapAclEntitySegmentTransferToEntity($aclEntitySegmentTransfer, $aclEntitySegmentEntity);

        $aclEntitySegmentEntity->save();

        return $aclEntitySegmentMapper
            ->mapAclEntitySegmentEntityToTransfer($aclEntitySegmentEntity, $aclEntitySegmentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleRequestTransfer $aclEntityRuleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleTransfer
     */
    public function createAclEntityRule(AclEntityRuleRequestTransfer $aclEntityRuleRequestTransfer): AclEntityRuleTransfer
    {
        $aclEntityRuleMapper = $this->getFactory()
            ->createAclEntityRuleMapper();

        $aclEntityRuleEntity = $aclEntityRuleMapper
            ->mapAclEntityRuleRequestTransferToEntity($aclEntityRuleRequestTransfer, new SpyAclEntityRule());

        $aclEntityRuleEntity->save();

        return $aclEntityRuleMapper
            ->mapAclEntityRuleEntityToAclEntityRuleTransfer($aclEntityRuleEntity, new AclEntityRuleTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer
     *
     * @return void
     */
    protected function createAclEntitySegmentRelations(AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer): void
    {
        $entityName = $aclEntitySegmentRequestTransfer->getEntity();

        foreach ($aclEntitySegmentRequestTransfer->getEntityIds() as $key => $entityIds) {
            foreach ($entityIds as $entityId) {
                /** @var \Orm\Zed\AclEntity\Persistence\Base\SpyAclEntityRule $aclEntitySegmentRelationEntity */
                $aclEntitySegmentRelationEntity = new $entityName();
                $aclEntitySegmentRelationEntity->setFkAclEntitySegment($aclEntitySegmentRequestTransfer->getIdAclEntitySegment());
                $aclEntitySegmentRelationEntity->fromArray([$key => $entityId]);

                $aclEntitySegmentRelationEntity->save();
            }
        }
    }
}
