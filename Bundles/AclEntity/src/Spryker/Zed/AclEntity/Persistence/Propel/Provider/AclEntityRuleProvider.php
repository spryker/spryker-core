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
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclRoleProviderInterface
     */
    protected $aclRoleProvider;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface
     */
    protected $aclEntityRepository;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclRoleProviderInterface $aclRoleProvider
     * @param \Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface $aclEntityRepository
     */
    public function __construct(
        AclRoleProviderInterface $aclRoleProvider,
        AclEntityRepositoryInterface $aclEntityRepository
    ) {
        $this->aclRoleProvider = $aclRoleProvider;
        $this->aclEntityRepository = $aclEntityRepository;
    }

    /**
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function getCurrentUserAclEntityRules(): AclEntityRuleCollectionTransfer
    {
        return $this->aclEntityRepository->getAclEntityRulesByRoles(
            $this->aclRoleProvider->getCurrentUserAclRoles(),
        );
    }
}
