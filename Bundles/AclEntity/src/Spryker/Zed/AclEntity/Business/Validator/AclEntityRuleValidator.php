<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Validator;

use Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Business\Exception\AclEntityRuleReferencedEntityNotFoundException;
use Spryker\Zed\AclEntity\Business\Exception\DuplicatedAclEntityRuleException;
use Spryker\Zed\AclEntity\Business\Exception\InheritedScopeCanNotBeAssignedException;
use Spryker\Zed\AclEntity\Business\Reader\AclEntityMetadataConfigReaderInterface;
use Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface;

class AclEntityRuleValidator implements AclEntityRuleValidatorInterface
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface
     */
    protected $aclEntityRepository;

    /**
     * @var \Spryker\Zed\AclEntity\Business\Reader\AclEntityMetadataConfigReaderInterface
     */
    protected $aclEntityMetadataConfigReader;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface $aclEntityRepository
     * @param \Spryker\Zed\AclEntity\Business\Reader\AclEntityMetadataConfigReaderInterface $aclEntityMetadataConfigReader
     */
    public function __construct(
        AclEntityRepositoryInterface $aclEntityRepository,
        AclEntityMetadataConfigReaderInterface $aclEntityMetadataConfigReader
    ) {
        $this->aclEntityRepository = $aclEntityRepository;
        $this->aclEntityMetadataConfigReader = $aclEntityMetadataConfigReader;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleTransfer $aclEntityRuleTransfer
     *
     * @return void
     */
    public function validate(AclEntityRuleTransfer $aclEntityRuleTransfer): void
    {
        $this->validateEntity($aclEntityRuleTransfer->getEntityOrFail());
        /** @var string $entity */
        $entity = $aclEntityRuleTransfer->getEntity();
        $this->validateScope($aclEntityRuleTransfer->getScopeOrFail(), $entity);
        $this->validateAclEntityRuleDuplication($aclEntityRuleTransfer);
    }

    /**
     * @param string $entity
     *
     * @throws \Spryker\Zed\AclEntity\Business\Exception\AclEntityRuleReferencedEntityNotFoundException
     *
     * @return void
     */
    protected function validateEntity(string $entity): void
    {
        if ($entity !== AclEntityConstants::WHILDCARD_ENTITY && !class_exists($entity)) {
            throw new AclEntityRuleReferencedEntityNotFoundException($entity);
        }
    }

    /**
     * @param string $scope
     * @param string $entity
     *
     * @throws \Spryker\Zed\AclEntity\Business\Exception\InheritedScopeCanNotBeAssignedException
     *
     * @return void
     */
    public function validateScope(string $scope, string $entity): void
    {
        if ($scope === AclEntityConstants::SCOPE_INHERITED) {
            $aclEntityMetadataCollection = $this->aclEntityMetadataConfigReader
                ->getAclEntityMetadataConfig(true)
                ->getAclEntityMetadataCollection();
            if (
                !$aclEntityMetadataCollection
                || !array_key_exists($entity, $aclEntityMetadataCollection->getCollection())
                || !$aclEntityMetadataCollection->getCollection()[$entity]->getParent()
            ) {
                throw new InheritedScopeCanNotBeAssignedException($entity);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleTransfer $aclEntityRuleTransfer
     *
     * @throws \Spryker\Zed\AclEntity\Business\Exception\DuplicatedAclEntityRuleException
     *
     * @return void
     */
    protected function validateAclEntityRuleDuplication(AclEntityRuleTransfer $aclEntityRuleTransfer): void
    {
        $aclEntityRuleCriteriaTransfer = (new AclEntityRuleCriteriaTransfer())
            ->addEntity($aclEntityRuleTransfer->getEntityOrFail())
            ->addIdAclRole($aclEntityRuleTransfer->getIdAclRoleOrFail())
            ->addScope($aclEntityRuleTransfer->getScopeOrFail())
            ->addPermissionMask($aclEntityRuleTransfer->getPermissionMaskOrFail());
        $idAclEntitySegment = $aclEntityRuleTransfer->getIdAclEntitySegment();
        if ($idAclEntitySegment) {
            $aclEntityRuleCriteriaTransfer->addIdAclEntitySegment($idAclEntitySegment);
        }

        $aclEntityRuleCollectionTransfer = $this->aclEntityRepository->getAclEntityRules($aclEntityRuleCriteriaTransfer);
        if ($aclEntityRuleCollectionTransfer->getAclEntityRules()->count()) {
            throw new DuplicatedAclEntityRuleException(
                $aclEntityRuleTransfer->getEntityOrFail(),
                $aclEntityRuleTransfer->getIdAclRoleOrFail(),
            );
        }
    }
}
