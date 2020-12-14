<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business\Adder;

use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthUser\Business\Exception\AclGroupNotFoundException;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToAclFacadeInterface;

class AclGroupAdder implements AclGroupAdderInterface
{
    /**
     * @var \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToAclFacadeInterface
     */
    protected $aclFacade;

    /**
     * @param \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToAclFacadeInterface $aclFacade
     */
    public function __construct(SecurityOauthUserToAclFacadeInterface $aclFacade)
    {
        $this->aclFacade = $aclFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $reference
     *
     * @throws \Spryker\Zed\SecurityOauthUser\Business\Exception\AclGroupNotFoundException
     *
     * @return void
     */
    public function addOauthUserToGroup(UserTransfer $userTransfer, string $reference): void
    {
        $groupCriteriaTransfer = (new GroupCriteriaTransfer())->setReference($reference);

        $groupTransfer = $this->aclFacade->findGroup($groupCriteriaTransfer);
        if (!$groupTransfer) {
            throw new AclGroupNotFoundException(sprintf(
                'The group with %s reference was not found.',
                $reference
            ));
        }

        $this->aclFacade->addUserToGroup($userTransfer->getIdUserOrFail(), $groupTransfer->getIdAclGroupOrFail());
    }
}
