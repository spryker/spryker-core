<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\AclGroup;

use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\MerchantUser\Business\Exception\AclGroupNotFoundException;
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
     * @throws \Spryker\Zed\MerchantUser\Business\Exception\AclGroupNotFoundException
     *
     * @return void
     */
    public function addMerchantAdminToGroup(MerchantUserTransfer $merchantUserTransfer, string $reference): void
    {
        $groupTransfer = $this->aclFacade->findGroup((new GroupCriteriaTransfer())->setReference($reference));

        if (!$groupTransfer) {
            throw new AclGroupNotFoundException(sprintf(
                'The group with %s reference was not found. Try run a "%s" command',
                $reference,
                'vendor/bin/console setup:init-db'
            ));
        }

        $this->aclFacade->addUserToGroup($merchantUserTransfer->getUserOrFail()->getIdUserOrFail(), $groupTransfer->getIdAclGroupOrFail());
    }
}
