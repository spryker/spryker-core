<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business;

use ArrayObject;
use Generated\Shared\Transfer\AclEntityMetadataConfigRequestTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer;
use Generated\Shared\Transfer\AclEntityRuleRequestTransfer;
use Generated\Shared\Transfer\AclEntityRuleResponseTransfer;
use Generated\Shared\Transfer\AclEntitySegmentCollectionTransfer;
use Generated\Shared\Transfer\AclEntitySegmentCriteriaTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclEntitySegmentResponseTransfer;
use Generated\Shared\Transfer\RolesTransfer;

interface AclEntityFacadeInterface
{
    /**
     * Specification:
     * - Retrieves ACL entity segments filtered by criteria from Persistence.
     * - Uses `AclEntitySegmentCriteriaTransfer.aclEntitySegmentConditions.aclEntitySegmentIds` to filter by ACL entity segment IDs.
     * - Uses `AclEntitySegmentCriteriaTransfer.aclEntitySegmentConditions.names` to filter by ACL entity segment names.
     * - Uses `AclEntitySegmentCriteriaTransfer.aclEntitySegmentConditions.references` to filter by ACL entity segment references.
     * - Uses `AclEntitySegmentCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `AclEntitySegmentCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `AclEntitySegmentCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `AclEntitySegmentCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Returns `AclEntitySegmentCollectionTransfer` filled with found ACL entity segments.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntitySegmentCriteriaTransfer $aclEntitySegmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentCollectionTransfer
     */
    public function getAclEntitySegmentCollection(
        AclEntitySegmentCriteriaTransfer $aclEntitySegmentCriteriaTransfer
    ): AclEntitySegmentCollectionTransfer;

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
     * - Retrieves ACL entity rules filtered by criteria from Persistence.
     * - Uses `AclEntityRuleCriteriaTransfer.aclEntityRuleCriteriaConditions.aclEntityRuleIds` to filter by ACL entity rule IDs.
     * - Uses `AclEntityRuleCriteriaTransfer.aclEntityRuleCriteriaConditions.aclEntitySegmentIds` to filter by ACL entity segment IDs.
     * - Uses `AclEntityRuleCriteriaTransfer.aclEntityRuleCriteriaConditions.aclRoleIds` to filter by ACL role IDs.
     * - Uses `AclEntityRuleCriteriaTransfer.aclEntityRuleCriteriaConditions.entities` to filter by ACL entity rule entities.
     * - Uses `AclEntityRuleCriteriaTransfer.aclEntityRuleCriteriaConditions.scopes` to filter by ACL entity rule scopes.
     * - Uses `AclEntityRuleCriteriaTransfer.aclEntityRuleCriteriaConditions.permissionMasks` to filter by ACL entity rule permission masks.
     * - Uses `AclEntityRuleCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `AclEntityRuleCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `AclEntityRuleCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `AclEntityRuleCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Returns `AclEntityRuleCollectionTransfer` filled with found ACL entity rules.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function getAclEntityRuleCollection(AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer): AclEntityRuleCollectionTransfer;

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
     * @param bool $runValidation
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigRequestTransfer|null $aclEntityMetadataConfigRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function getAclEntityMetadataConfig(
        bool $runValidation = true,
        ?AclEntityMetadataConfigRequestTransfer $aclEntityMetadataConfigRequestTransfer = null
    ): AclEntityMetadataConfigTransfer;

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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @return void
     */
    public function saveAclEntityRules(ArrayObject $aclEntityRuleTransfers): void;
}
