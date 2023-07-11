<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Builder;

use Generated\Shared\Transfer\DynamicEntityConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Map\DatabaseMap;

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
     * @param \Generated\Shared\Transfer\DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQueryWithFieldConditions(
        ModelCriteria $query,
        DynamicEntityConditionsTransfer $dynamicEntityConditionsTransfer,
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
    ): ModelCriteria {
        if ($dynamicEntityConditionsTransfer->getFieldConditions()->getArrayCopy() === []) {
            return $query;
        }

        $definedFieldNames = $this->collectDefinedFieldNames($dynamicEntityDefinitionTransfer);

        foreach ($dynamicEntityConditionsTransfer->getFieldConditions() as $fieldCondition) {
            $fieldConfitionName = $fieldCondition->getNameOrFail();

            if ($fieldConfitionName === static::IDENTIFIER_KEY) {
                $fieldConfitionName = $dynamicEntityDefinitionTransfer->getIdentifierOrFail();
            }

            if (!in_array($fieldConfitionName, $definedFieldNames)) {
                continue;
            }

            $query->filterBy($this->convertSnakeCaseToCamelCase($fieldConfitionName), $fieldCondition->getValue());
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
}
