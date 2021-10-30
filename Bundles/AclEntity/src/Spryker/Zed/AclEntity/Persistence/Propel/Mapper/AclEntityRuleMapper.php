<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Generated\Shared\Transfer\AclEntityRuleRequestTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Orm\Zed\AclEntity\Persistence\SpyAclEntityRule;
use Propel\Runtime\Collection\Collection;
use Spryker\Service\AclEntity\AclEntityServiceInterface;

class AclEntityRuleMapper
{
    /**
     * @var \Spryker\Service\AclEntity\AclEntityServiceInterface
     */
    protected $aclEntityService;

    /**
     * @param \Spryker\Service\AclEntity\AclEntityServiceInterface $aclEntityService
     */
    public function __construct(AclEntityServiceInterface $aclEntityService)
    {
        $this->aclEntityService = $aclEntityService;
    }

    /**
     * @param \Orm\Zed\AclEntity\Persistence\SpyAclEntityRule $aclEntityRuleEntity
     * @param \Generated\Shared\Transfer\AclEntityRuleTransfer $aclEntityRuleTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleTransfer
     */
    public function mapAclEntityRuleEntityToAclEntityRuleTransfer(
        SpyAclEntityRule $aclEntityRuleEntity,
        AclEntityRuleTransfer $aclEntityRuleTransfer
    ): AclEntityRuleTransfer {
        $aclEntityRuleTransfer = $aclEntityRuleTransfer->fromArray(
            $aclEntityRuleEntity->toArray(),
            true,
        );
        $aclEntityRuleTransfer->setIdAclRole($aclEntityRuleEntity->getFkAclRole());
        $aclEntityRuleTransfer->setIdAclEntitySegment($aclEntityRuleEntity->getFkAclEntitySegment());

        return $aclEntityRuleTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection|\Orm\Zed\AclEntity\Persistence\SpyAclEntityRule[] $aclEntityRuleEntities
     * @param \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function mapAclEntityRuleCollectionToAclEntityRuleCollectionTransfer(
        Collection $aclEntityRuleEntities,
        AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer
    ): AclEntityRuleCollectionTransfer {
        /** @var \Orm\Zed\AclEntity\Persistence\SpyAclEntityRule $aclEntityRuleEntity */
        foreach ($aclEntityRuleEntities as $aclEntityRuleEntity) {
            $aclEntityRuleCollectionTransfer->addAclEntityRule(
                $this->mapAclEntityRuleEntityToAclEntityRuleTransfer($aclEntityRuleEntity, new AclEntityRuleTransfer()),
            );
        }

        return $aclEntityRuleCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleRequestTransfer $aclEntityRuleRequestTransfer
     * @param \Orm\Zed\AclEntity\Persistence\SpyAclEntityRule $aclEntityRuleEntity
     *
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntityRule
     */
    public function mapAclEntityRuleRequestTransferToEntity(
        AclEntityRuleRequestTransfer $aclEntityRuleRequestTransfer,
        SpyAclEntityRule $aclEntityRuleEntity
    ): SpyAclEntityRule {
        $aclEntityRuleEntity->fromArray($aclEntityRuleRequestTransfer->toArray(false));

        if ($aclEntityRuleRequestTransfer->getIdAclRole()) {
            $aclEntityRuleEntity->setFkAclRole($aclEntityRuleRequestTransfer->getIdAclRoleOrFail());
        }
        if ($aclEntityRuleRequestTransfer->getIdAclEntitySegment()) {
            $aclEntityRuleEntity->setFkAclEntitySegment($aclEntityRuleRequestTransfer->getIdAclEntitySegmentOrFail());
        }

        return $aclEntityRuleEntity;
    }
}
