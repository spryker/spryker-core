<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface;

class AclRoleCreator implements AclRoleCreatorInterface
{
    /**
     * @var \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface
     */
    protected AclMerchantPortalToAclFacadeInterface $aclFacade;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Creator\AclEntitySegmentCreatorInterface
     */
    protected AclEntitySegmentCreatorInterface $aclEntitySegmentCreator;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Creator\AclRuleCreatorInterface
     */
    protected AclRuleCreatorInterface $aclRuleCreator;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Creator\AclEntityRuleCreatorInterface
     */
    protected AclEntityRuleCreatorInterface $aclEntityRuleCreator;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface
     */
    protected AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface $aclFacade
     * @param \Spryker\Zed\AclMerchantPortal\Business\Creator\AclEntitySegmentCreatorInterface $aclEntitySegmentCreator
     * @param \Spryker\Zed\AclMerchantPortal\Business\Creator\AclRuleCreatorInterface $aclRuleCreator
     * @param \Spryker\Zed\AclMerchantPortal\Business\Creator\AclEntityRuleCreatorInterface $aclEntityRuleCreator
     * @param \Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator
     */
    public function __construct(
        AclMerchantPortalToAclFacadeInterface $aclFacade,
        AclEntitySegmentCreatorInterface $aclEntitySegmentCreator,
        AclRuleCreatorInterface $aclRuleCreator,
        AclEntityRuleCreatorInterface $aclEntityRuleCreator,
        AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator
    ) {
        $this->aclFacade = $aclFacade;
        $this->aclEntitySegmentCreator = $aclEntitySegmentCreator;
        $this->aclRuleCreator = $aclRuleCreator;
        $this->aclEntityRuleCreator = $aclEntityRuleCreator;
        $this->aclMerchantPortalGenerator = $aclMerchantPortalGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function createMerchantAclRole(MerchantTransfer $merchantTransfer): RoleTransfer
    {
        $roleTransfer = (new RoleTransfer())
            ->setName($this->aclMerchantPortalGenerator->generateAclMerchantRoleName($merchantTransfer))
            ->setReference($this->aclMerchantPortalGenerator->generateAclMerchantReference($merchantTransfer));

        $roleTransfer = $this->aclFacade->createRole($roleTransfer);
        $aclEntitySegmentTransfer = $this->aclEntitySegmentCreator->createMerchantAclEntitySegment($merchantTransfer);

        return $roleTransfer
            ->setAclRules(new ArrayObject($this->aclRuleCreator->createMerchantAclRules($roleTransfer)))
            ->setAclEntityRules(new ArrayObject($this->aclEntityRuleCreator->createMerchantAclEntityRules($roleTransfer, $aclEntitySegmentTransfer)));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function createMerchantUserAclRole(MerchantUserTransfer $merchantUserTransfer): RoleTransfer
    {
        $roleTransfer = (new RoleTransfer())
            ->setName($this->aclMerchantPortalGenerator->generateAclMerchantUserRoleName($merchantUserTransfer))
            ->setReference($this->aclMerchantPortalGenerator->generateAclMerchantUserReference($merchantUserTransfer->getUserOrFail()));

        $roleTransfer = $this->aclFacade->createRole($roleTransfer);
        $aclEntitySegmentTransfer = $this->aclEntitySegmentCreator->createMerchantUserAclEntitySegment($merchantUserTransfer);

        return $roleTransfer
            ->setAclRules(new ArrayObject($this->aclRuleCreator->createMerchantUserAclRules($roleTransfer)))
            ->setAclEntityRules(new ArrayObject($this->aclEntityRuleCreator->createMerchantUserAclEntityRules($roleTransfer, $aclEntitySegmentTransfer)));
    }
}
