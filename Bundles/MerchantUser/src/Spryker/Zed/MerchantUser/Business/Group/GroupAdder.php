<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Group;

use Generated\Shared\Transfer\GroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAclFacadeInterface;

class GroupAdder implements GroupAdderInterface
{
    use LoggerTrait;

    protected const GROUP_NOT_FOUND_ERROR_MESSAGE = 'The group was not found.';

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
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function addMerchantAdminToGroupByReference(MerchantUserTransfer $merchantUserTransfer, string $reference): MerchantUserResponseTransfer
    {
        $groupTransfer = $this->aclFacade->findGroup((new GroupCriteriaFilterTransfer())->setReference($reference));

        if (!$groupTransfer) {
            if ($this->getLogger()) {
                $this->getLogger()->error(sprintf(
                    'The group with %s reference was not found. Try run a "%s" command',
                    $reference,
                    'vendor/bin/console setup:init-db'
                ));
            }

            return (new MerchantUserResponseTransfer())
                ->setIsSuccessful(false)
                ->setMerchantUser($merchantUserTransfer)
                ->addError((new MessageTransfer())->setMessage(static::GROUP_NOT_FOUND_ERROR_MESSAGE));
        }

        $this->aclFacade->addUserToGroup($merchantUserTransfer->getUser()->getIdUser(), $groupTransfer->getIdAclGroup());

        return (new MerchantUserResponseTransfer())->setIsSuccessful(true);
    }
}
