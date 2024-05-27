<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;

interface DynamicEntityEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function createDynamicEntityConfiguration(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityConfigurationTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function updateDynamicEntityConfiguration(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityConfigurationTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function createDynamicEntity(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function createChildDynamicEntity(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function updateDynamicEntity(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function updateChildDynamicEntity(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer,
        DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $childDynamicEntityConfigurationCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $parentDynamicEntityConfigurationTransfer
     * @param array<string, array<string, mixed>> $indexedChildRelations
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function createDynamicEntityConfigurationRelation(
        DynamicEntityConfigurationCollectionTransfer $childDynamicEntityConfigurationCollectionTransfer,
        DynamicEntityConfigurationTransfer $parentDynamicEntityConfigurationTransfer,
        array $indexedChildRelations
    ): DynamicEntityConfigurationCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function deleteDynamicEntity(
        DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityCollectionResponseTransfer;
}
