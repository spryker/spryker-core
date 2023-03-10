<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Adder;

use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig;
use Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface;

class GroupAdder implements GroupAdderInterface
{
    /**
     * @var \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface
     */
    protected AclMerchantPortalToAclFacadeInterface $aclFacade;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface
     */
    protected AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig
     */
    protected AclMerchantPortalConfig $aclMerchantPortalConfig;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface $aclFacade
     * @param \Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator
     * @param \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig $aclMerchantPortalConfig
     */
    public function __construct(
        AclMerchantPortalToAclFacadeInterface $aclFacade,
        AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator,
        AclMerchantPortalConfig $aclMerchantPortalConfig
    ) {
        $this->aclFacade = $aclFacade;
        $this->aclMerchantPortalGenerator = $aclMerchantPortalGenerator;
        $this->aclMerchantPortalConfig = $aclMerchantPortalConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Generated\Shared\Transfer\GroupTransfer $merchantUserGroupTransfer
     *
     * @return void
     */
    public function addMerchantUserToGroups(MerchantUserTransfer $merchantUserTransfer, GroupTransfer $merchantUserGroupTransfer): void
    {
        $this->addMerchantUserToMerchantGroup($merchantUserTransfer);
        $this->addMerchantUserToProductViewerGroup($merchantUserTransfer);
        $this->addMerchantUserToMerchantUserGroup($merchantUserTransfer, $merchantUserGroupTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return void
     */
    protected function addMerchantUserToMerchantGroup(MerchantUserTransfer $merchantUserTransfer): void
    {
        $aclMerchantReference = $this->aclMerchantPortalGenerator->generateAclMerchantReference(
            $merchantUserTransfer->getMerchantOrFail(),
        );
        $groupCriteriaTransfer = (new GroupCriteriaTransfer())
            ->setReference($aclMerchantReference);

        $merchantGroupTransfer = $this->aclFacade->findGroup($groupCriteriaTransfer);

        if (!$merchantGroupTransfer) {
            return;
        }

        $this->aclFacade->addUserToGroup(
            $merchantUserTransfer->getIdUserOrFail(),
            $merchantGroupTransfer->getIdAclGroupOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return void
     */
    protected function addMerchantUserToProductViewerGroup(MerchantUserTransfer $merchantUserTransfer): void
    {
        $groupCriteriaTransfer = (new GroupCriteriaTransfer())
            ->setReference($this->aclMerchantPortalConfig->getProductViewerForOfferCreationAclRoleReference());

        $productViewerGroupTransfer = $this->aclFacade->findGroup($groupCriteriaTransfer);

        if (!$productViewerGroupTransfer) {
            return;
        }

        $this->aclFacade->addUserToGroup(
            $merchantUserTransfer->getIdUserOrFail(),
            $productViewerGroupTransfer->getIdAclGroupOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Generated\Shared\Transfer\GroupTransfer $merchantUserGroupTransfer
     *
     * @return void
     */
    protected function addMerchantUserToMerchantUserGroup(
        MerchantUserTransfer $merchantUserTransfer,
        GroupTransfer $merchantUserGroupTransfer
    ): void {
        $this->aclFacade->addUserToGroup(
            $merchantUserTransfer->getIdUserOrFail(),
            $merchantUserGroupTransfer->getIdAclGroupOrFail(),
        );
    }
}
