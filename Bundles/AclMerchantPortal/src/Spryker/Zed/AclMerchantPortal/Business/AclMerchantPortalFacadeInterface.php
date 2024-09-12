<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface AclMerchantPortalFacadeInterface
{
    /**
     * Specification:
     * - Creates ACL group for provided merchant.
     * - Creates ACL role, ACL rules, ACL entity rules for provided merchant.
     * - Creates ACL entity segment for provided merchant.
     * - Returns `MerchantResponse` transfer object.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\AclMerchantPortal\Business\AclMerchantPortalFacadeInterface::createAclEntitiesForMerchant()} instead.
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function createMerchantAclData(MerchantTransfer $merchantTransfer): MerchantResponseTransfer;

    /**
     * Specification:
     * - Creates ACL group for provided merchant user.
     * - Creates ACL role, ACL rules, ACL entity rules for provided merchant user.
     * - Creates ACL entity segment for provided merchant user.
     * - Returns `MerchantUser` transfer object.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\AclMerchantPortal\Business\AclMerchantPortalFacadeInterface::createAclEntitiesForMerchantUser()} instead.
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function createMerchantUserAclData(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer;

    /**
     * Specification:
     * - Expands provided `AclEntityMetadataConfig` transfer object with merchant order composite data.
     * - Expands provided `AclEntityMetadataConfig` transfer object with merchant product composite data.
     * - Expands provided `AclEntityMetadataConfig` transfer object with merchant composite data.
     * - Expands provided `AclEntityMetadataConfig` transfer object with product offer composite data.
     * - Expands provided `AclEntityMetadataConfig` transfer object with global read entities configuration.
     * - Expands provided `AclEntityMetadataConfig` transfer object with allow list entities.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\AclMerchantPortal\Business\AclMerchantPortalFacadeInterface::expandAclEntityConfiguration()} instead.
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfig(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer;

    /**
     * Specification:
     * - Returns true if the filtered role is not configured as Backoffice login authentication role.
     * - Returns false if the user has ACL group with Backoffice access, true otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $role
     *
     * @return bool
     */
    public function checkUserRoleFilterCondition(UserTransfer $userTransfer, string $role): bool;

    /**
     * Specification:
     * - Requires `MerchantUser.idUser` to be provided.
     * - Returns `true` if the given `MerchantUser` transfer has a group with a `root_group` name, `false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return bool
     */
    public function isMerchantUserLoginRestricted(MerchantUserTransfer $merchantUserTransfer): bool;

    /**
     * Specification:
     * - Expects `Merchant.merchantReference` and `Merchant.name` to be set.
     * - Requires `Merchant.idMerchant` to be provided.
     * - Creates ACL entity segment for provided merchant.
     * - Executes {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclRuleExpanderPluginInterface} plugin stack.
     * - Executes {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclEntityRuleExpanderPluginInterface} plugin stack.
     * - Creates ACL role, ACL rules, ACL entity rules, ACL group for provided merchant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function createAclEntitiesForMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer;

    /**
     * Specification:
     * - Requires `MerchantUser.idMerchantUser` to be provided.
     * - Requires `MerchantUser.user.idUser`, `MerchantUser.user.firstName` and `MerchantUser.user.lastName` to be provided.
     * - Requires `MerchantUser.merchant.name` and `MerchantUser.merchant.merchantReference` to be provided.
     * - Creates ACL entity segment for provided merchant user.
     * - Executes {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclRuleExpanderPluginInterface} plugin stack.
     * - Executes {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclEntityRuleExpanderPluginInterface} plugin stack.
     * - Creates ACL role, ACL rules, ACL entity rules, ACL group for provided merchant user.
     * - Finds merchant, product-viewer groups.
     * - Adds merchant user to merchant, product-viewer, merchant-user groups.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function createAclEntitiesForMerchantUser(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer;

    /**
     * Specification:
     * - Expands provided `AclEntityMetadataConfig` transfer object with event behavior composite data.
     * - Executes {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface} plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityConfiguration(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer;

    /**
     * Specification:
     * - Verifies whether users are associated with ACL groups listed in
     * {@link \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig::getBackofficeAllowedAclGroupNames()}.
     * - Sets `null` to the response data under the `assistUser` keys for users belonging to these groups.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function expandAgentDashboardMerchantUserTableData(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer
    ): GuiTableDataResponseTransfer;

    /**
     * Specification:
     * - Loops over all merchants and their users.
     * - Uses the transaction mechanism to avoid partial updates.
     * - Creates or updates ACL entities for each merchant and their users.
     * - Creates ACL entity segment for each merchant if it's not created yet.
     * - Executes {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclRuleExpanderPluginInterface} plugin stack.
     * - Executes {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclEntityRuleExpanderPluginInterface} plugin stack.
     * - Creates or updates ACL role, ACL rules, ACL entity rules, ACL group for each merchant.
     * - Creates ACL entity segment for each merchant user if it's not created yet.
     * - Uses the following configuration to control the merchant read batch size {@link \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig::getAclEntitySynchronizationMerchantReadBatchSize()}.
     * - Executes {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclRuleExpanderPluginInterface} plugin stack.
     * - Executes {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclEntityRuleExpanderPluginInterface} plugin stack.
     * - Creates or updates ACL role, ACL rules, ACL entity rules, ACL group for each merchant user.
     * - Adds merchant user to merchant ACL group.
     * - Uses the following configuration to control the merchant user read batch size {@link \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig::getAclEntitySynchronizationMerchantUserReadBatchSize()}.
     *
     * @api
     *
     * @return void
     */
    public function synchronizeAclEntitiesForMerchantsAndMerchantUsers(): void;
}
