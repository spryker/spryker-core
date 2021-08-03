<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business;

use ArrayObject;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityRuleRequestTransfer;
use Generated\Shared\Transfer\AclEntityRuleResponseTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclEntitySegmentResponseTransfer;
use Generated\Shared\Transfer\RolesTransfer;

interface AclEntityFacadeInterface
{
    /**
     * Specification:
     * - Creates an `AclEntitySegment` entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentResponseTransfer
     */
    public function createAclEntitySegment(AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer): AclEntitySegmentResponseTransfer;

    /**
     * Specification:
     * - Creates an `AclEntityRule` entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityRuleRequestTransfer $aclEntityRuleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleResponseTransfer
     */
    public function createAclEntityRule(AclEntityRuleRequestTransfer $aclEntityRuleRequestTransfer): AclEntityRuleResponseTransfer;

    /**
     * Specification:
     * - Returns `AclEntityMetadataConfigTransfer`.
     * - Executes `AclEntityMetadataConfigExpanderPluginInterface` plugin stack to expand collection.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function getAclEntityMetadataConfig(): AclEntityMetadataConfigTransfer;

    /**
     * Specification:
     * - Returns whether `AclEntity` behavior is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Specification:
     * - Expands `Role` transfer objects with ACL entity rules.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function expandAclRoles(RolesTransfer $rolesTransfer): RolesTransfer;

    /**
     * Specification:
     * - Saves ACL entity rule collection.
     *
     * @api
     *
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\AclEntityRuleTransfer[] $aclEntityRuleTransfers
     *
     * @return void
     */
    public function saveAclEntityRules(ArrayObject $aclEntityRuleTransfers): void;
}
