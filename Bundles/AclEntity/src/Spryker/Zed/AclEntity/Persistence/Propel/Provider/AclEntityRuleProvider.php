<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Provider;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface;

class AclEntityRuleProvider implements AclEntityRuleProviderInterface
{
    protected static ?AclEntityRuleCollectionTransfer $aclEntityRuleCollectionTransfer = null;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclRoleProviderInterface $aclRoleProvider
     * @param \Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface $aclEntityRepository
     */
    public function __construct(
        protected AclRoleProviderInterface $aclRoleProvider,
        protected AclEntityRepositoryInterface $aclEntityRepository
    ) {
    }

    /**
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function getCurrentUserAclEntityRules(): AclEntityRuleCollectionTransfer
    {
        if (static::$aclEntityRuleCollectionTransfer === null) {
            static::$aclEntityRuleCollectionTransfer = $this->executeGetCurrentUserAclEntityRules();
        }

        return static::$aclEntityRuleCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    protected function executeGetCurrentUserAclEntityRules(): AclEntityRuleCollectionTransfer
    {
        return $this->aclEntityRepository->getAclEntityRulesByRoles(
            $this->aclRoleProvider->getCurrentUserAclRoles(),
        );
    }
}
