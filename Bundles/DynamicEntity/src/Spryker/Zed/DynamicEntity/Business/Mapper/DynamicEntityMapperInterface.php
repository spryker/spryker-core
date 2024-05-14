<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Mapper;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;

interface DynamicEntityMapperInterface
{
    /**
     * @param array<string, mixed> $dynamicEntityConfiguration
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function mapDynamicEntityConfigurationToDynamicEntityConfigurationTransfer(
        array $dynamicEntityConfiguration,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityConfigurationTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $childDynamicEntityCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    public function mapChildDynamicEntityCollectionTransferToDynamicEntityCollectionTransfer(
        DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer,
        DynamicEntityCollectionTransfer $childDynamicEntityCollectionTransfer,
        DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
    ): DynamicEntityCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string> $childMapping
     *
     * @return array<string>
     */
    public function getDynamicEntityConfigurationRelationMappedFields(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $childMapping
    ): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
     *
     * @return array<string, array<int|string>>
     */
    public function getForeignKeysGroupedByChildFileldName(
        DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer,
        DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
    ): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer
     */
    public function mapDynamicEntityCollectionRequestTransferToDynamicEntityCriteriaTransfer(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCriteriaTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer>
     */
    public function mapDynamicEntityCollectionResponseTransferToPostEditRequestTransfersArray(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): array;

    /**
     * @param string $tableAlias
     * @param array<int, array<mixed>> $entityFieldsCollection
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $originalDynamicEntityCollectionRequestTransfer
     * @param array<\Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranchInterface> $dynamicEntityCollectionRequestTreeBranches
     *
     * @return array<\Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranchInterface>
     */
    public function mapDynamicEntityCollectionRequestTransfersToCollectionRequestTreeBranches(
        string $tableAlias,
        array $entityFieldsCollection,
        DynamicEntityCollectionRequestTransfer $originalDynamicEntityCollectionRequestTransfer,
        array $dynamicEntityCollectionRequestTreeBranches = []
    ): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConditionsTransfer|null
     */
    public function mapDynamicEntityTransferToDynamicEntityConditionsTransfer(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): ?DynamicEntityConditionsTransfer;
}
