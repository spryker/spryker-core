<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
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
     * - Requires `MerchantUserTransfer.idUser` to be provided.
     * - Returns `true` if the given `MerchantUser` transfer has a group with a `root_group` name, `false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return bool
     */
    public function isMerchantUserLoginRestricted(MerchantUserTransfer $merchantUserTransfer): bool;
}
