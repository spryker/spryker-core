<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Builder;

use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

interface DynamicEntityQueryBuilderInterface
{
    /**
     * @param string $tableName
     *
     * @return string|null
     */
    public function getEntityClassName(string $tableName): ?string;

    /**
     * @param string $tableName
     *
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityModelNotFoundException
     *
     * @return string
     */
    public function assertEntityClassNameExists(string $tableName): string;

    /**
     * @param string $tableName
     *
     * @return string
     */
    public function getEntityQueryClass(string $tableName): string;

    /**
     * @param string $tableName
     *
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityModelNotFoundException
     *
     * @return string
     */
    public function assertEntityQueryClassNameExists(string $tableName): string;

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     * @param array<string, array<int|string>> $foreignKeyFieldMappingArray
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQueryWithFieldConditions(
        ModelCriteria $query,
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer,
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer,
        array $foreignKeyFieldMappingArray = []
    ): ModelCriteria;
}
