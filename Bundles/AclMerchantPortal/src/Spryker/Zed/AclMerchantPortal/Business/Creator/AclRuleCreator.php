<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Creator;

use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface;

class AclRuleCreator implements AclRuleCreatorInterface
{
    /**
     * @var \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface
     */
    protected AclMerchantPortalToAclFacadeInterface $aclFacade;

    /**
     * @var list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclRuleExpanderPluginInterface>
     */
    protected array $merchantAclRuleExpanderPlugins;

    /**
     * @var list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclRuleExpanderPluginInterface>
     */
    protected array $merchantUserAclRuleExpanderPlugins;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface $aclFacade
     * @param list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclRuleExpanderPluginInterface> $merchantAclRuleExpanderPlugins
     * @param list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclRuleExpanderPluginInterface> $merchantUserAclRuleExpanderPlugins
     */
    public function __construct(
        AclMerchantPortalToAclFacadeInterface $aclFacade,
        array $merchantAclRuleExpanderPlugins,
        array $merchantUserAclRuleExpanderPlugins
    ) {
        $this->aclFacade = $aclFacade;
        $this->merchantAclRuleExpanderPlugins = $merchantAclRuleExpanderPlugins;
        $this->merchantUserAclRuleExpanderPlugins = $merchantUserAclRuleExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return list<\Generated\Shared\Transfer\RuleTransfer>
     */
    public function createMerchantAclRules(RoleTransfer $roleTransfer): array
    {
        $ruleTransfers = $this->executeMerchantAclRuleExpanderPlugins([]);
        foreach ($ruleTransfers as $ruleTransfer) {
            $ruleTransfer->setFkAclRole($roleTransfer->getIdAclRole());
            $this->aclFacade->addRule($ruleTransfer);
        }

        return $ruleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return list<\Generated\Shared\Transfer\RuleTransfer>
     */
    public function createMerchantUserAclRules(RoleTransfer $roleTransfer): array
    {
        $ruleTransfers = $this->executeMerchantUserAclRuleExpanderPlugins([]);
        foreach ($ruleTransfers as $ruleTransfer) {
            $ruleTransfer->setFkAclRole($roleTransfer->getIdAclRole());
            $this->aclFacade->addRule($ruleTransfer);
        }

        return $ruleTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\RuleTransfer> $ruleTransfers
     *
     * @return list<\Generated\Shared\Transfer\RuleTransfer>
     */
    protected function executeMerchantAclRuleExpanderPlugins(array $ruleTransfers): array
    {
        foreach ($this->merchantAclRuleExpanderPlugins as $merchantAclRuleExpanderPlugin) {
            $ruleTransfers = $merchantAclRuleExpanderPlugin->expand($ruleTransfers);
        }

        return $ruleTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\RuleTransfer> $ruleTransfers
     *
     * @return list<\Generated\Shared\Transfer\RuleTransfer>
     */
    protected function executeMerchantUserAclRuleExpanderPlugins(array $ruleTransfers): array
    {
        foreach ($this->merchantUserAclRuleExpanderPlugins as $merchantUserAclRuleExpanderPlugin) {
            $ruleTransfers = $merchantUserAclRuleExpanderPlugin->expand($ruleTransfers);
        }

        return $ruleTransfers;
    }
}
