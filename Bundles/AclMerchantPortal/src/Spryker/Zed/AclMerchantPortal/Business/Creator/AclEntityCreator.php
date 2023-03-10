<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Creator;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\MerchantErrorTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Spryker\Zed\AclMerchantPortal\Business\Adder\GroupAdderInterface;
use Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface;

class AclEntityCreator implements AclEntityCreatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_REFERENCE = 'Merchant reference not found';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_NAME = 'Merchant name not found';

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface
     */
    protected AclMerchantPortalToAclFacadeInterface $aclFacade;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface
     */
    protected AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Creator\AclRoleCreatorInterface
     */
    protected AclRoleCreatorInterface $aclRoleCreator;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Adder\GroupAdderInterface
     */
    protected GroupAdderInterface $groupAdder;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface $aclFacade
     * @param \Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator
     * @param \Spryker\Zed\AclMerchantPortal\Business\Creator\AclRoleCreatorInterface $aclRoleCreator
     * @param \Spryker\Zed\AclMerchantPortal\Business\Adder\GroupAdderInterface $groupAdder
     */
    public function __construct(
        AclMerchantPortalToAclFacadeInterface $aclFacade,
        AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator,
        AclRoleCreatorInterface $aclRoleCreator,
        GroupAdderInterface $groupAdder
    ) {
        $this->aclFacade = $aclFacade;
        $this->aclMerchantPortalGenerator = $aclMerchantPortalGenerator;
        $this->aclRoleCreator = $aclRoleCreator;
        $this->groupAdder = $groupAdder;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function createAclEntitiesForMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        if (!$merchantTransfer->getMerchantReference()) {
            return $this->createErrorMerchantResponseTransfer(static::ERROR_MESSAGE_MERCHANT_REFERENCE);
        }

        if (!$merchantTransfer->getName()) {
            return $this->createErrorMerchantResponseTransfer(static::ERROR_MESSAGE_MERCHANT_NAME);
        }

        $rolesTransfer = (new RolesTransfer())
            ->addRole($this->aclRoleCreator->createMerchantAclRole($merchantTransfer));

        $groupTransfer = (new GroupTransfer())
            ->setName($this->aclMerchantPortalGenerator->generateAclMerchantGroupName($merchantTransfer))
            ->setReference($this->aclMerchantPortalGenerator->generateAclMerchantReference($merchantTransfer));

        $this->aclFacade->createGroup($groupTransfer, $rolesTransfer);

        return (new MerchantResponseTransfer())
            ->setMerchant($merchantTransfer)
            ->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function createAclEntitiesForMerchantUser(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer
    {
        $rolesTransfer = (new RolesTransfer())
            ->addRole($this->aclRoleCreator->createMerchantUserAclRole($merchantUserTransfer));

        $groupTransfer = (new GroupTransfer())
            ->setName($this->aclMerchantPortalGenerator->generateAclMerchantUserGroupName($merchantUserTransfer))
            ->setReference($this->aclMerchantPortalGenerator->generateAclMerchantUserReference($merchantUserTransfer->getUserOrFail()));

        $merchantUserGroupTransfer = $this->aclFacade->createGroup($groupTransfer, $rolesTransfer);
        $this->groupAdder->addMerchantUserToGroups($merchantUserTransfer, $merchantUserGroupTransfer);

        return $merchantUserTransfer;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    protected function createErrorMerchantResponseTransfer(string $message): MerchantResponseTransfer
    {
        $merchantErrorTransfer = (new MerchantErrorTransfer())
            ->setMessage($message);

        return (new MerchantResponseTransfer())
            ->setIsSuccess(false)
            ->addError($merchantErrorTransfer);
    }
}
