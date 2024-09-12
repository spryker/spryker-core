<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Filter;

use Generated\Shared\Transfer\AclEntityRuleCriteriaConditionsTransfer;
use Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeInterface;

class AclEntityRuleFilter implements AclEntityRuleFilterInterface
{
    /**
     * @var string
     */
    protected const INDEX_KEY_DELIMITER = '_';

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeInterface
     */
    protected AclMerchantPortalToAclEntityFacadeInterface $aclEntityFacade;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeInterface $aclEntityFacade
     */
    public function __construct(AclMerchantPortalToAclEntityFacadeInterface $aclEntityFacade)
    {
        $this->aclEntityFacade = $aclEntityFacade;
    }

    /**
     * @param list<\Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @return list<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function filterOutExistingAclEntityRules(array $aclEntityRuleTransfers): array
    {
        $aclEntityRuleCriteriaConditionsTransfer = $this->createCriteriaConditionsTransfer($aclEntityRuleTransfers);
        $persistedAclEntityRuleTransfers = $this->aclEntityFacade->getAclEntityRuleCollection(
            (new AclEntityRuleCriteriaTransfer())->setAclEntityRuleCriteriaConditions($aclEntityRuleCriteriaConditionsTransfer),
        );

        $indexedAclEntityRuleTransfers = $this->indexAclEntityRuleTransfers($aclEntityRuleTransfers);
        $indexedPersistedAclEntityRuleTransfers = $this->indexAclEntityRuleTransfers($persistedAclEntityRuleTransfers->getAclEntityRules()->getArrayCopy());

        ksort($indexedAclEntityRuleTransfers);
        ksort($indexedPersistedAclEntityRuleTransfers);

        return array_values(array_diff_key($indexedAclEntityRuleTransfers, $indexedPersistedAclEntityRuleTransfers));
    }

    /**
     * @param list<\Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCriteriaConditionsTransfer
     */
    protected function createCriteriaConditionsTransfer(
        array $aclEntityRuleTransfers
    ): AclEntityRuleCriteriaConditionsTransfer {
        $aclEntityRuleCriteriaConditionsTransfer = new AclEntityRuleCriteriaConditionsTransfer();
        foreach ($aclEntityRuleTransfers as $aclEntityRuleTransfer) {
            $aclEntityRuleCriteriaConditionsTransfer
                ->addEntity($aclEntityRuleTransfer->getEntityOrFail())
                ->addIdAclRole($aclEntityRuleTransfer->getIdAclRoleOrFail())
                ->addScope($aclEntityRuleTransfer->getScopeOrFail())
                ->addPermissionMask($aclEntityRuleTransfer->getPermissionMaskOrFail());

            if ($aclEntityRuleTransfer->getIdAclEntitySegment()) {
                $aclEntityRuleCriteriaConditionsTransfer->addIdAclEntitySegment($aclEntityRuleTransfer->getIdAclEntitySegment());
            }
        }

        return $aclEntityRuleCriteriaConditionsTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    protected function indexAclEntityRuleTransfers(array $aclEntityRuleTransfers): array
    {
        $indexedAclEntityRuleTransfers = [];

        foreach ($aclEntityRuleTransfers as $aclEntityRuleTransfer) {
            $indexKey = $this->getAclEntityIndexKey($aclEntityRuleTransfer);
            $indexedAclEntityRuleTransfers[$indexKey] = $aclEntityRuleTransfer;
        }

        return $indexedAclEntityRuleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleTransfer $aclEntityRuleTransfer
     *
     * @return string
     */
    protected function getAclEntityIndexKey(AclEntityRuleTransfer $aclEntityRuleTransfer): string
    {
        $indexKey = $aclEntityRuleTransfer->getEntityOrFail() . static::INDEX_KEY_DELIMITER .
            $aclEntityRuleTransfer->getIdAclRoleOrFail() . static::INDEX_KEY_DELIMITER .
            $aclEntityRuleTransfer->getScopeOrFail() . static::INDEX_KEY_DELIMITER .
            $aclEntityRuleTransfer->getPermissionMaskOrFail();

        if ($aclEntityRuleTransfer->getIdAclEntitySegment()) {
            $indexKey .= static::INDEX_KEY_DELIMITER . $aclEntityRuleTransfer->getIdAclEntitySegment();
        }

        return $indexKey;
    }
}
