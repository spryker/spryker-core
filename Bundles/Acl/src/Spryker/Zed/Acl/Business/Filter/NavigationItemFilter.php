<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\NavigationItemCollectionTransfer;
use Generated\Shared\Transfer\NavigationItemTransfer;
use Spryker\Zed\Acl\Business\Model\RuleInterface;
use Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface;

class NavigationItemFilter implements NavigationItemFilterInterface
{
    /**
     * @var \Spryker\Zed\Acl\Business\Model\RuleInterface
     */
    protected $rule;

    /**
     * @var \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\Acl\Business\Model\RuleInterface $rule
     * @param \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface $userFacade
     */
    public function __construct(RuleInterface $rule, AclToUserInterface $userFacade)
    {
        $this->rule = $rule;
        $this->userFacade = $userFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    public function filterNavigationItemCollectionByAccessibility(
        NavigationItemCollectionTransfer $navigationItemCollectionTransfer
    ): NavigationItemCollectionTransfer {
        if (!$this->userFacade->hasCurrentUser()) {
            return new NavigationItemCollectionTransfer();
        }

        $userTransfer = $this->userFacade->getCurrentUser();
        $navigationItemTransfers = $navigationItemCollectionTransfer->getNavigationItems()->getArrayCopy();

        foreach ($navigationItemTransfers as $navigationItemKey => $navigationItemTransfer) {
            if (!$this->isNavigationItemTransferValidForAclAccessCheck($navigationItemTransfer)) {
                continue;
            }

            $isNavigationItemAllowed = $this->rule->isAllowed(
                $userTransfer,
                $navigationItemTransfer->getModule(),
                $navigationItemTransfer->getController(),
                $navigationItemTransfer->getAction()
            );

            if (!$isNavigationItemAllowed) {
                unset($navigationItemTransfers[$navigationItemKey]);
            }
        }

        return $navigationItemCollectionTransfer->setNavigationItems(
            new ArrayObject($navigationItemTransfers)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationItemTransfer $navigationItemTransfer
     *
     * @return bool
     */
    protected function isNavigationItemTransferValidForAclAccessCheck(
        NavigationItemTransfer $navigationItemTransfer
    ): bool {
        return $navigationItemTransfer->getModule()
            && $navigationItemTransfer->getController()
            && $navigationItemTransfer->getAction();
    }
}
