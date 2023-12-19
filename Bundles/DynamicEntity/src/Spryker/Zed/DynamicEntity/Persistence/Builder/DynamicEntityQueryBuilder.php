<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Builder;

use Generated\Shared\Transfer\DynamicEntityConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Map\DatabaseMap;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class DynamicEntityQueryBuilder implements DynamicEntityQueryBuilderInterface
{
    /**
     * @var string
     */
    protected const QUERY_CLASS_PLACEHOLDER = '%sQuery';

    /**
     * @var string
     */
    protected const IDENTIFIER_KEY = 'identifier';

    /**
     * @var \Propel\Runtime\Map\DatabaseMap
     */
    protected DatabaseMap $databaseMap;

    /**
     * @param \Propel\Runtime\Map\DatabaseMap $databaseMap
     */
    public function __construct(DatabaseMap $databaseMap)
    {
        $this->databaseMap = $databaseMap;
    }

    /**
     * @param string $tableName
     *
     * @return string|null
     */
    public function getEntityClassName(string $tableName): ?string
    {
        return $this->databaseMap->getTable($tableName)->getClassName();
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    public function getEntityQueryClass(string $tableName): string
    {
        return sprintf(static::QUERY_CLASS_PLACEHOLDER, $this->getEntityClassName($tableName));
    }

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
    ): ModelCriteria {
        if ($dynamicEntityCriteriaTransfer->getDynamicEntityConditions() !== null) {
            $query = $this->filterByFieldConditions(
                $query,
                $dynamicEntityDefinitionTransfer,
                $dynamicEntityCriteriaTransfer->getDynamicEntityConditions(),
            );
        }

        if ($foreignKeyFieldMappingArray === []) {
            return $query;
        }

        foreach ($foreignKeyFieldMappingArray as $fieldConditionName => $fieldConditionValues) {
            $query->filterBy($this->convertSnakeCaseToCamelCase($fieldConditionName), $fieldConditionValues, Criteria::IN);
        }

        return $query;
    }

    /**
     * @param string $input
     *
     * @return string
     */
    protected function convertSnakeCaseToCamelCase(string $input): string
    {
        return str_replace('_', '', ucwords($input, '_'));
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return array<string>
     */
    protected function collectDefinedFieldNames(DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer): array
    {
        $definedFieldNames = [];

        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinition) {
            $definedFieldNames[] = $fieldDefinition->getFieldNameOrFail();
        }

        return $definedFieldNames;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConditionsTransfer|null $dynamicEntityConditionsTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function filterByFieldConditions(
        ModelCriteria $query,
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer,
        ?DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer
    ): ModelCriteria {
        if ($dynamicEntityConditionsTransfer === null || $dynamicEntityConditionsTransfer->getFieldConditions()->getArrayCopy() === []) {
            return $query;
        }

        $definedFieldNames = $this->collectDefinedFieldNames($dynamicEntityDefinitionTransfer);

        foreach ($dynamicEntityConditionsTransfer->getFieldConditions() as $fieldCondition) {
            $fieldConditionName = $fieldCondition->getNameOrFail();

            if ($fieldConditionName === static::IDENTIFIER_KEY) {
                $fieldConditionName = $dynamicEntityDefinitionTransfer->getIdentifierOrFail();
            }

            if (!in_array($fieldConditionName, $definedFieldNames)) {
                continue;
            }

            $query->filterBy($this->convertSnakeCaseToCamelCase($fieldConditionName), $fieldCondition->getValue());
        }

        return $query;
    }
}
