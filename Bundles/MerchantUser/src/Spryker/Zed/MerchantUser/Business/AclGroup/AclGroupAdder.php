<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\AclGroup;

use Generated\Shared\Transfer\GroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Propel\Runtime\Exception\EntityNotFoundException;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAclFacadeInterface;

class AclGroupAdder implements AclGroupAdderInterface
{
    /**
     * @var \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAclFacadeInterface
     */
    protected $aclFacade;

    /**
     * @param \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAclFacadeInterface $aclFacade
     */
    public function __construct(MerchantUserToAclFacadeInterface $aclFacade)
    {
        $this->aclFacade = $aclFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param string $reference
     *
     * @throws \Propel\Runtime\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function addMerchantAdminToGroupByReference(MerchantUserTransfer $merchantUserTransfer, string $reference): MerchantUserResponseTransfer
    {
        $groupTransfer = $this->aclFacade->findGroup((new GroupCriteriaFilterTransfer())->setReference($reference));

        if (!$groupTransfer) {
            throw new EntityNotFoundException(sprintf(
                'The group with %s reference was not found. Try run a "%s" command',
                $reference,
                'vendor/bin/console setup:init-db'
            ));
        }

        $this->aclFacade->addUserToGroup($merchantUserTransfer->getUser()->getIdUser(), $groupTransfer->getIdAclGroup());

        return (new MerchantUserResponseTransfer())->setIsSuccessful(true);
    }
}
