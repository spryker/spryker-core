<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\AclEntitySegmentTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Zed\AclMerchantPortal\Business\Filter\AclEntityRuleFilterInterface;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeInterface;

class AclEntityRuleCreator implements AclEntityRuleCreatorInterface
{
    /**
     * @uses \Spryker\Shared\AclEntity\AclEntityConstants::SCOPE_SEGMENT
     *
     * @var string
     */
    protected const SCOPE_SEGMENT = 'segment';

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeInterface
     */
    protected AclMerchantPortalToAclEntityFacadeInterface $aclEntityFacade;

    /**
     * @var list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclEntityRuleExpanderPluginInterface>
     */
    protected array $merchantAclEntityRuleExpanderPlugins;

    /**
     * @var list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclEntityRuleExpanderPluginInterface>
     */
    protected array $merchantUserAclEntityRuleExpanderPlugins;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Filter\AclEntityRuleFilterInterface
     */
    protected AclEntityRuleFilterInterface $aclEntityRuleFilter;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeInterface $aclEntityFacade
     * @param \Spryker\Zed\AclMerchantPortal\Business\Filter\AclEntityRuleFilterInterface $aclEntityRuleFilter
     * @param list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclEntityRuleExpanderPluginInterface> $merchantAclEntityRuleExpanderPlugins
     * @param list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclEntityRuleExpanderPluginInterface> $merchantUserAclEntityRuleExpanderPlugins
     */
    public function __construct(
        AclMerchantPortalToAclEntityFacadeInterface $aclEntityFacade,
        AclEntityRuleFilterInterface $aclEntityRuleFilter,
        array $merchantAclEntityRuleExpanderPlugins,
        array $merchantUserAclEntityRuleExpanderPlugins
    ) {
        $this->aclEntityFacade = $aclEntityFacade;
        $this->merchantAclEntityRuleExpanderPlugins = $merchantAclEntityRuleExpanderPlugins;
        $this->merchantUserAclEntityRuleExpanderPlugins = $merchantUserAclEntityRuleExpanderPlugins;
        $this->aclEntityRuleFilter = $aclEntityRuleFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     * @param \Generated\Shared\Transfer\AclEntitySegmentTransfer $aclEntitySegmentTransfer
     *
     * @return list<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function createMerchantAclEntityRules(
        RoleTransfer $roleTransfer,
        AclEntitySegmentTransfer $aclEntitySegmentTransfer
    ): array {
        $aclEntityRuleTransfers = $this->executeMerchantAclEntityRuleExpanderPlugins([]);
        $aclEntityRuleTransfers = $this->expandAclEntityRulesWithRoleAndSegment($aclEntityRuleTransfers, $roleTransfer, $aclEntitySegmentTransfer);
        $aclEntityRuleTransfers = $this->aclEntityRuleFilter->filterOutExistingAclEntityRules($aclEntityRuleTransfers);
        if (!$aclEntityRuleTransfers) {
            return [];
        }

        $this->aclEntityFacade->saveAclEntityRules(new ArrayObject($aclEntityRuleTransfers));

        return $aclEntityRuleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     * @param \Generated\Shared\Transfer\AclEntitySegmentTransfer $aclEntitySegmentTransfer
     *
     * @return list<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function createMerchantUserAclEntityRules(
        RoleTransfer $roleTransfer,
        AclEntitySegmentTransfer $aclEntitySegmentTransfer
    ): array {
        $aclEntityRuleTransfers = $this->executeMerchantUserAclEntityRuleExpanderPlugins([]);
        $aclEntityRuleTransfers = $this->expandAclEntityRulesWithRoleAndSegment($aclEntityRuleTransfers, $roleTransfer, $aclEntitySegmentTransfer);
        $aclEntityRuleTransfers = $this->aclEntityRuleFilter->filterOutExistingAclEntityRules($aclEntityRuleTransfers);
        if (!$aclEntityRuleTransfers) {
            return [];
        }

        $this->aclEntityFacade->saveAclEntityRules(new ArrayObject($aclEntityRuleTransfers));

        return $aclEntityRuleTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @return list<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    protected function executeMerchantAclEntityRuleExpanderPlugins(array $aclEntityRuleTransfers): array
    {
        foreach ($this->merchantAclEntityRuleExpanderPlugins as $merchantAclEntityRuleExpanderPlugin) {
            $aclEntityRuleTransfers = $merchantAclEntityRuleExpanderPlugin->expand($aclEntityRuleTransfers);
        }

        return $aclEntityRuleTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @return list<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    protected function executeMerchantUserAclEntityRuleExpanderPlugins(array $aclEntityRuleTransfers): array
    {
        foreach ($this->merchantUserAclEntityRuleExpanderPlugins as $merchantUserAclEntityRuleExpanderPlugin) {
            $aclEntityRuleTransfers = $merchantUserAclEntityRuleExpanderPlugin->expand($aclEntityRuleTransfers);
        }

        return $aclEntityRuleTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     * @param \Generated\Shared\Transfer\AclEntitySegmentTransfer $aclEntitySegmentTransfer
     *
     * @return list<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function expandAclEntityRulesWithRoleAndSegment(
        array $aclEntityRuleTransfers,
        RoleTransfer $roleTransfer,
        AclEntitySegmentTransfer $aclEntitySegmentTransfer
    ): array {
        foreach ($aclEntityRuleTransfers as $aclEntityRuleTransfer) {
            $aclEntityRuleTransfer->setIdAclRole($roleTransfer->getIdAclRole());

            if ($aclEntityRuleTransfer->getScope() === static::SCOPE_SEGMENT) {
                $aclEntityRuleTransfer->setIdAclEntitySegment($aclEntitySegmentTransfer->getIdAclEntitySegmentOrFail());
            }
        }

        return $aclEntityRuleTransfers;
    }
}
