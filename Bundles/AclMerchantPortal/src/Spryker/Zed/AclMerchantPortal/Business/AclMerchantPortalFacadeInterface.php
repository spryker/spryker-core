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
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function createMerchantUserAclData(MerchantUserTransfer $merchantTransfer): MerchantUserTransfer;

    /**
     * Specification:
     * - Expands provided `AclEntityMetadataConfig` transfer object with merchant product composite data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithMerchantProductComposite(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer;

    /**
     * Specification:
     * - Expands provided `AclEntityMetadataConfig` transfer object with merchant composite data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithMerchantComposite(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer;

    /**
     * Specification:
     * - Expands provided `AclEntityMetadataConfig` transfer object with product offer composite data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithProductOfferComposite(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer;

    /**
     * Specification:
     * - Expands provided `AclEntityMetadataConfig` transfer object with merchant read global entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithMerchantReadGlobalEntities(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer;

    /**
     * Specification:
     * - Expands provided `AclEntityMetadataConfig` transfer object with whitelisted classes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithWhitelist(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer;
}
