<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence;

use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;

interface DynamicEntityRepositoryInterface
{
    /**
     * @param string $tableAlias
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer|null
     */
    public function findDynamicEntityConfigurationByTableAlias(string $tableAlias): ?DynamicEntityConfigurationTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function getDynamicEntityConfigurationByDynamicEntityCriteria(
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
    ): DynamicEntityConfigurationCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, array<int|string>> $foreignKeyFieldMappingArray
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    public function getEntities(
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $foreignKeyFieldMappingArray = []
    ): DynamicEntityCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function getDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
    ): DynamicEntityConfigurationCollectionTransfer;

    /**
     * @param array<int, string> $tableNames
     * @param array<int, string> $tableAliases
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function getDynamicEntityConfigurationCollectionByTableAliasesOrTableNames(
        array $tableNames = [],
        array $tableAliases = []
    ): DynamicEntityConfigurationCollectionTransfer;
}
