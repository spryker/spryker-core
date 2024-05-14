<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Indexer;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;

interface DynamicEntityIndexerInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<string, string> $indexedFieldDefinitions
     *
     * @return array<string, string>
     */
    public function getFieldValuesIndexedByFieldName(
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $indexedFieldDefinitions
    ): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return array<string, string>
     */
    public function getFieldNamesIndexedByFieldVisibleName(
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
    ): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer>
     */
    public function getChildRelationsIndexedByRelationName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer>
     */
    public function getChildRelationsIndexedByTableAlias(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer>
     */
    public function getDefinitionsIndexedByFieldVisibleName(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    public function getConfigurationsIndexedByTableAlias(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array;
}
